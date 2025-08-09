<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        $subdomain = $this->extractSubdomain($host);
        
        if (!$subdomain) {
            return $next($request);
        }
        
        $tenant = Tenant::where('subdomain', $subdomain)->first();
        
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }
        
        if (!$tenant->isActive()) {
            abort(503, 'Tenant is not active');
        }
        
        // Make tenant current for this request
        $tenant->makeCurrent();
        
        // Configure database connection for tenant
        config(['database.connections.tenant.database' => $tenant->database_name]);
        
        return $next($request);
    }
    
    private function extractSubdomain(string $host): ?string
    {
        $parts = explode('.', $host);
        
        if (count($parts) < 3) {
            return null;
        }
        
        $subdomain = $parts[0];
        
        // Skip reserved subdomains
        $reserved = ['www', 'admin', 'landlord', 'api', 'mail', 'ftp', 'cpanel'];
        if (in_array($subdomain, $reserved)) {
            return null;
        }
        
        return $subdomain;
    }
}



