@extends('onboarding.layout')

@section('content')
<div class="max-w-md mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Company Details</h2>
        <p class="text-gray-600">Tell us about your organization.</p>
    </div>

    <form method="POST" action="{{ route('onboarding.step3') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $session->token ?? '' }}">

        <div class="mb-4">
            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
            <input type="text" 
                   id="company_name" 
                   name="company_name" 
                   value="{{ old('company_name', $session->company_name ?? '') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('company_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-2">Subdomain</label>
            <div class="flex">
                <input type="text" 
                       id="subdomain" 
                       name="subdomain" 
                       value="{{ old('subdomain', $session->subdomain ?? '') }}"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       required>
                <span class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-500 text-sm">
                    .myapp.test
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500">Only lowercase letters, numbers, and hyphens allowed.</p>
            @error('subdomain')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-between">
            <a href="{{ route('onboarding.step2', ['token' => $session->token ?? '']) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Continue
            </button>
        </div>
    </form>
</div>
@endsection



