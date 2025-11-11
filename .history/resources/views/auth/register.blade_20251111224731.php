<x-guest-layout>
    <!-- Header -->
    <div class="flex items-center justify-center gap-6 mb-10">
        <img src="{{ asset('images/repairo-logo.png') }}" alt="Repairo Logo"
             class="w-32 h-32"> 
        <div class="text-left">
            <h2 class="text-4xl font-bold text-gray-900 tracking-tight">Create Your Account</h2>
            <p class="text-gray-500 mt-2 text-sm">Join Repairo to manage and track your repairs easily.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" type="text" name="name"
                class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-md transition-all duration-200 placeholder-gray-400"
                :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" type="text" name="phone"
                class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-md transition-all duration-200 placeholder-gray-400"
                :value="old('phone')" required autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" type="email" name="email"
                class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-md transition-all duration-200 placeholder-gray-400"
                :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password"
                class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-md transition-all duration-200 placeholder-gray-400"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-md transition-all duration-200 placeholder-gray-400"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit -->
        <div class="mt-8">
            <button type="submit"
                class="w-full bg-[#1800ad] text-white font-semibold py-3 rounded-md hover:bg-[#0f008a] active:scale-[0.98] transition-all duration-150">
                {{ __('Register') }}
            </button>
        </div>

        <!-- Login Redirect -->
        <div class="text-center mt-6 text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[#1800ad] font-medium hover:underline hover:text-[#0f008a] transition-colors">Log in</a>
        </div>
    </form>
</x-guest-layout>
