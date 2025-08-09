@extends('onboarding.layout')

@section('content')
<div class="max-w-md mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Billing Information</h2>
        <p class="text-gray-600">Please provide your billing details.</p>
    </div>

    <form method="POST" action="{{ route('onboarding.step4') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $session->token ?? '' }}">

        <div class="mb-4">
            <label for="billing_name" class="block text-sm font-medium text-gray-700 mb-2">Billing Name</label>
            <input type="text" 
                   id="billing_name" 
                   name="billing_name" 
                   value="{{ old('billing_name', $session->billing_info['billing_name'] ?? '') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('billing_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <textarea id="address" 
                      name="address" 
                      rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                      required>{{ old('address', $session->billing_info['address'] ?? '') }}</textarea>
            @error('address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
            <input type="text" 
                   id="country" 
                   name="country" 
                   value="{{ old('country', $session->billing_info['country'] ?? '') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('country')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   value="{{ old('phone', $session->billing_info['phone'] ?? '') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-between">
            <a href="{{ route('onboarding.step3', ['token' => $session->token ?? '']) }}" 
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



