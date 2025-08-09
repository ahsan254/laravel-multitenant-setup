@extends('onboarding.layout')

@section('content')
<div class="max-w-md mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Confirmation</h2>
        <p class="text-gray-600">Please review your information before we create your workspace.</p>
    </div>

    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
        <div class="space-y-2 text-sm">
            <div><span class="font-medium">Name:</span> {{ $session->full_name }}</div>
            <div><span class="font-medium">Email:</span> {{ $session->email }}</div>
        </div>
    </div>

    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Details</h3>
        <div class="space-y-2 text-sm">
            <div><span class="font-medium">Company:</span> {{ $session->company_name }}</div>
            <div><span class="font-medium">Subdomain:</span> {{ $session->subdomain }}.myapp.test</div>
        </div>
    </div>

    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing Information</h3>
        <div class="space-y-2 text-sm">
            <div><span class="font-medium">Name:</span> {{ $session->billing_info['billing_name'] ?? '' }}</div>
            <div><span class="font-medium">Address:</span> {{ $session->billing_info['address'] ?? '' }}</div>
            <div><span class="font-medium">Country:</span> {{ $session->billing_info['country'] ?? '' }}</div>
            <div><span class="font-medium">Phone:</span> {{ $session->billing_info['phone'] ?? '' }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('onboarding.step5') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $session->token ?? '' }}">

        <div class="flex justify-between">
            <a href="{{ route('onboarding.step4', ['token' => $session->token ?? '']) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Create Workspace
            </button>
        </div>
    </form>
</div>
@endsection



