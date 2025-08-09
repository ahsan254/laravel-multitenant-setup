<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\OnboardingSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
            Log::info("🚀 Starting provisioning for tenant: {$this->tenant->subdomain}");

            // Step 1: Mark tenant as provisioning
            $this->tenant->update(['status' => 'provisioning']);

            // Step 2: Create dedicated tenant database
            $this->createTenantDatabase();

            // Step 3: Run tenant-specific migrations
            $this->runTenantMigrations();

            // Step 4: Create initial admin user
            $this->createInitialUser();

            // Step 5: Add hosts file entry (Local only)
            $this->addHostsFileEntry();

            // Step 6: Mark tenant as active
            $this->tenant->update([
                'status' => 'active',
                'provisioned_at' => now()
            ]);

            Log::info("✅ Tenant provisioning completed: {$this->tenant->subdomain}");

        } catch (\Throwable $e) {
            Log::error("❌ Tenant provisioning failed for {$this->tenant->subdomain}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->tenant->update(['status' => 'failed']);

            throw $e;
        }
    }

    private function createTenantDatabase(): void
    {
        $dbName = $this->tenant->database_name;

        DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        Log::info("📂 Created database: {$dbName}");
    }

    private function runTenantMigrations(): void
    {
        config(['database.connections.tenant.database' => $this->tenant->database_name]);

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true
        ]);

        Log::info("📜 Migrations completed for tenant DB: {$this->tenant->database_name}");
    }

    private function createInitialUser(): void
    {
        config(['database.connections.tenant.database' => $this->tenant->database_name]);

        DB::connection('tenant')->table('users')->insert([
            'name' => $this->session->full_name,
            'email' => $this->session->email,
            'password' => $this->session->password_hash ?? Hash::make('password123'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info("👤 Initial user created for tenant: {$this->tenant->subdomain}");
    }

    /**
     * Add tenant subdomain to local hosts file (only in local env)
     */
    private function addHostsFileEntry(): void
    {
        if (!app()->environment('local')) {
            return; // skip in non-local environments
        }

        $subdomain = "{$this->tenant->subdomain}.myapp.test";
        $hostsPath = PHP_OS_FAMILY === 'Windows'
            ? 'C:\Windows\System32\drivers\etc\hosts'
            : '/etc/hosts';

        $entry = "127.0.0.1   {$subdomain}";

        $hostsContent = file_get_contents($hostsPath);

        if (strpos($hostsContent, $subdomain) === false) {
            file_put_contents($hostsPath, PHP_EOL . $entry, FILE_APPEND);
            Log::info("📝 Added hosts entry: {$entry}");
        } else {
            Log::info("ℹ Hosts entry already exists for: {$subdomain}");
        }
    }
}
