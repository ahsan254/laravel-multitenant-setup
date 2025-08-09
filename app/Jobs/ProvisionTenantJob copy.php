<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\OnboardingSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ProvisionTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;

    public function __construct(
        public Tenant $tenant,
        public OnboardingSession $session
    ) {}

    public function handle(): void
    {
        try {
            Log::info("Starting tenant provisioning for: {$this->tenant->subdomain}");

            // Update tenant status to provisioning
            $this->tenant->update(['status' => 'provisioning']);

            // Create tenant database
            $this->createTenantDatabase();

            // Run migrations for tenant database
            $this->runTenantMigrations();

            // Create initial user
            $this->createInitialUser();

            // Update tenant status to active
            $this->tenant->update([
                'status' => 'active',
                'provisioned_at' => now()
            ]);

            Log::info("Tenant provisioning completed for: {$this->tenant->subdomain}");

        } catch (\Exception $e) {
            Log::error("Tenant provisioning failed for {$this->tenant->subdomain}: " . $e->getMessage());
            
            $this->tenant->update(['status' => 'failed']);
            
            throw $e;
        }
    }

    private function createTenantDatabase(): void
    {
        $databaseName = $this->tenant->database_name;
        
        // Create database
        DB::statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}`");
        
        Log::info("Created database: {$databaseName}");
    }

    private function runTenantMigrations(): void
    {
        $databaseName = $this->tenant->database_name;
        
        // Configure connection for tenant database
        config(['database.connections.tenant.database' => $databaseName]);
        
        // Run migrations on tenant database
        $this->artisan('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true
        ]);
        
        Log::info("Migrations completed for database: {$databaseName}");
    }

    private function createInitialUser(): void
    {
        $databaseName = $this->tenant->database_name;
        
        // Configure connection for tenant database
        config(['database.connections.tenant.database' => $databaseName]);
        
        // Create user in tenant database
        DB::connection('tenant')->table('users')->insert([
            'name' => $this->session->full_name,
            'email' => $this->session->email,
            'password' => $this->session->password_hash,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        Log::info("Created initial user for tenant: {$this->tenant->subdomain}");
    }

    private function artisan(string $command, array $parameters = []): void
    {
        $artisan = base_path('artisan');
        $command = "php {$artisan} {$command}";
        
        foreach ($parameters as $key => $value) {
            $command .= " --{$key}={$value}";
        }
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new \Exception("Artisan command failed: {$command}");
        }
    }
}



