<x-guest-layout>
    <div class="w-full sm:max-w-4xl bg-white shadow-lg rounded-2xl border border-gray-100 p-0 overflow-hidden flex flex-col sm:flex-row">
        <!-- Left: Logo Section -->
        <div class="flex items-center justify-center bg-gradient-to-br from-[#1800ad] to-[#0f008a] sm:w-1/2 w-full p-10">
            <img src="{{ asset('images/repairo-logo.png') }}" alt="Repairo Logo"
                 class="w-72 h-72 object-contain drop-shadow-xl">
        </div>

        <!-- Right: Form Section -->
        <div class="sm:w-1/2 w-full p-8 sm:p-10 flex flex-col justify-center">
            <!-- Header -->
            <div class="mb-8 text-center sm:text-left">
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Create Your Account</h2>
                <p class="text-gray-500 mt-1 text-sm">Join Repairo to manage and track your repairs easily.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" type="text" name="name"
                        class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-xl shadow-sm"
                        :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Phone -->
                <div>
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    <x-text-input id="phone" type="text" name="phone"
                        class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-xl shadow-sm"
                        :value="old('phone')" required autocomplete="tel" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" />
                    <x-text-input id="email" type="email" name="email"
                        class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-xl shadow-sm"
                        :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" type="password" name="password"
                        class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-xl shadow-sm"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                        class="block mt-1 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-xl shadow-sm"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit -->
                <div class="mt-8">
                    <button type="submit"
                        class="w-full bg-[#1800ad] text-white font-semibold py-3 rounded-xl shadow-md hover:bg-[#0f008a] active:scale-[0.98] transition-all duration-150">
                        {{ __('Register') }}
                    </button>
                </div>

                <!-- Login Redirect -->
                <div class="text-center mt-6 text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#1800ad] font-medium hover:underline">Log in</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
