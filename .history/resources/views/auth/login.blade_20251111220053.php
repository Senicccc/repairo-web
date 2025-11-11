<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-white">
        <!-- Logo -->
        <div class="mb-8">
            <img src="{{ asset('images/logo-repairo.png') }}" alt="Repairo Logo" class="h-20 w-auto">
        </div>

        <!-- Card -->
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <!-- Title -->
            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">
                Welcome Back
            </h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-lg" 
                                  type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-lg" 
                                  type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#1800ad] shadow-sm focus:ring-[#1800ad]" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Login Button -->
                <div class="mt-6">
                    <button type="submit" 
                        class="w-full bg-[#1800ad] text-white font-semibold py-2.5 rounded-lg hover:bg-[#0f008a] transition-all duration-200 shadow-sm">
                        {{ __('Log in') }}
                    </button>
                </div>

                <!-- Register Link -->
                <div class="flex items-center justify-center mt-6">
                    <a href="{{ route('register') }}" 
                       class="text-sm text-gray-600 hover:text-[#1800ad] transition-all duration-200">
                        {{ __("Don't have an account? Register") }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <p class="mt-8 text-sm text-gray-400">
            © {{ date('Y') }} Repairo. All rights reserved.
        </p>
    </div>
</x-guest-layout>
