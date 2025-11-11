<x-guest-layout>
    <!-- Header -->
    <div class="flex items-center justify-center gap-6 mb-10">
        <img src="{{ asset('images/repairo-logo.png') }}" 
             alt="Repairo Logo" 
             class="w- h-32 drop-shadow-md"> 
        <div class="text-left">
            <h2 class="text-4xl font-bold text-gray-900 tracking-tight">Welcome Back</h2>
            <p class="text-gray-500 mt-2 text-sm">Log in to continue managing your repairs effortlessly.</p>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" type="email" name="email"
                class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-xl shadow-sm"
                :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password"
                class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-xl shadow-sm"
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between text-sm mt-2">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#1800ad] shadow-sm focus:ring-[#1800ad]" name="remember">
                <span class="ms-2 text-gray-600">Remember me</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-[#1800ad] hover:underline">
                Forgot password?
            </a>
        </div>

        <!-- Submit -->
        <div class="mt-8">
            <button type="submit"
                class="w-full bg-[#1800ad] text-white font-semibold py-3 rounded-xl shadow-md hover:bg-[#0f008a] active:scale-[0.98] transition-all duration-150">
                {{ __('Log In') }}
            </button>
        </div>

        <!-- Register -->
        <div class="text-center mt-6 text-sm text-gray-600">
            Don’t have an account? 
            <a href="{{ route('register') }}" class="text-[#1800ad] font-medium hover:underline">Create one</a>
        </div>
    </form>
</x-guest-layout>
