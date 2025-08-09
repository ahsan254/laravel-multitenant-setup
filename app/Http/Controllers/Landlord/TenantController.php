<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::orderBy('created_at', 'desc')->paginate(20);
        
        return view('landlord.tenants.index', compact('tenants'));
    }

    public function show(Tenant $tenant)
    {
        return view('landlord.tenants.show', compact('tenant'));
    }

    public function destroy(Tenant $tenant)
    {
        try {
            // Delete tenant database
            \DB::statement("DROP DATABASE IF EXISTS `{$tenant->database_name}`");
            
            // Delete tenant record
            $tenant->delete();
            
            return redirect()->route('landlord.tenants.index')
                ->with('success', 'Tenant deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete tenant: ' . $e->getMessage());
        }
    }
}



