@extends('onboarding.layout')

@section('content')
<div class="max-w-md mx-auto text-center">
    <div class="mb-8">
        <div class="bg-green-100 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Workspace Created Successfully!</h2>
        <p class="text-gray-600">Your workspace is being provisioned. This may take a few minutes.</p>
    </div>

    <div class="bg-blue-50 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Workspace Details</h3>
        <div class="space-y-2 text-sm">
            <div><span class="font-medium">Company:</span> {{ $session->company_name }}</div>
            <div><span class="font-medium">Subdomain:</span> {{ $session->subdomain }}.myapp.test</div>
            <div><span class="font-medium">Email:</span> {{ $session->email }}</div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h4 class="font-medium text-yellow-800 mb-2">What happens next?</h4>
            <ul class="text-sm text-yellow-700 space-y-1">
                <li>• Your isolated database is being created</li>
                <li>• Initial user account is being set up</li>
                <li>• Workspace configuration is being applied</li>
                <li>• You'll receive an email when ready</li>
            </ul>
        </div>

        <div class="flex justify-center">
            <a href="http://{{ $session->subdomain }}.myapp.test" 
               target="_blank"
               class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Go to Your Workspace
            </a>
        </div>
    </div>
</div>
@endsection
