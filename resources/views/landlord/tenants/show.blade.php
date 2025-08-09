@extends('landlord.layout')

@section('header')
    Tenant Details: {{ $tenant->name }}
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                                <dd class="text-sm text-gray-900">{{ $tenant->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Subdomain</dt>
                                <dd class="text-sm text-gray-900">{{ $tenant->subdomain }}.myapp.test</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Database Name</dt>
                                <dd class="text-sm text-gray-900">{{ $tenant->database_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($tenant->status === 'active') bg-green-100 text-green-800
                                        @elseif($tenant->status === 'provisioning') bg-yellow-100 text-yellow-800
                                        @elseif($tenant->status === 'failed') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($tenant->status) }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="text-sm text-gray-900">{{ $tenant->created_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="text-sm text-gray-900">{{ $tenant->updated_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            @if($tenant->provisioned_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Provisioned</dt>
                                    <dd class="text-sm text-gray-900">{{ $tenant->provisioned_at->format('M j, Y g:i A') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                @if($tenant->data)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Data</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <pre class="text-sm text-gray-700 overflow-x-auto">{{ json_encode($tenant->data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif

                <div class="mt-8 flex space-x-4">
                    <a href="{{ route('landlord.tenants.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Back to List
                    </a>
                    <form action="{{ route('landlord.tenants.destroy', $tenant) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this tenant?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Delete Tenant
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



