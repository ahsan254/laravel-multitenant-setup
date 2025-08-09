@extends('onboarding.layout')

@section('content')
<div class="max-w-md mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Account Information</h2>
        <p class="text-gray-600">Let's start by collecting your basic information.</p>
    </div>

    <form method="POST" action="{{ route('onboarding.step1') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $session->token ?? '' }}">

        <div class="mb-4">
            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
            <input type="text" 
                   id="full_name" 
                   name="full_name" 
                   value="{{ old('full_name', $session->full_name ?? '') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('full_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="{{ old('email', $session->email ?? '') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Continue
            </button>
        </div>
    </form>
</div>
@endsection



