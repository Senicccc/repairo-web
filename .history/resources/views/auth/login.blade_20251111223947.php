<x-guest-layout>
    <div class="flex flex-col items-center text-center mb-10 animate-fade-in">
        <img src="{{ asset('images/repairo-logo.png') }}" 
             alt="Repairo Logo" 
             class="w-32 h-32 mb-6 drop-shadow-xl transition-transform duration-500 hover:scale-110 hover:drop-shadow-2xl">
        <h2 class="text-4xl font-bold text-gray-900 tracking-tight">Welcome Back</h2>
        <p class="text-gray-500 mt-2 text-base">Sign in to continue to your Repairo dashboard.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" 
          class="space-y-6 animate-slide-up bg-white/80 backdrop-blur-md border border-gray-100 p-8 rounded-2xl shadow-md transition-all duration-300 hover:shadow-lg">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="font-medium text-gray-700" />
            <x-text-input id="email" type="email" name="email"
                class="block mt-2 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-xl shadow-sm transition-all duration-200 placeholder-gray-400"
                :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="font-medium text-gray-700" />
            <x-text-input id="password" type="password" name="password"
                class="block mt-2 w-full border-gray-300 focus:border-[#1800ad] focus:ring-[#1800ad] rounded-xl shadow-sm transition-all duration-200 placeholder-gray-400"
                required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember -->
        <div class="flex items-center justify-between text-sm mt-2">
            <label for="remember_me" class="inline-flex items-center select-none cursor-pointer">
                <input id="remember_me" type="checkbox" 
                       class="rounded border-gray-300 text-[#1800ad] shadow-sm focus:ring-[#1800ad]" 
                       name="remember">
                <span class="ms-2 text-gray-600">Remember me</span>
            </label>
        </div>

        <!-- Submit -->
        <div class="mt-6">
            <button type="submit"
                class="w-full bg-gradient-to-r from-[#1800ad] to-[#3a26ff] text-white font-semibold py-3 rounded-xl shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                {{ __('Log In') }}
            </button>
        </div>

        <!-- Register -->
        <div class="text-center mt-6 text-sm text-gray-600">
            Don’t have an account? 
            <a href="{{ route('register') }}" class="text-[#1800ad] font-medium hover:underline hover:text-[#0f008a] transition-colors">
                Create one
            </a>
        </div>
    </form>

    <!-- Animations -->
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-10px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 0.7s ease-out; }
        .animate-slide-up { animation: slide-up 0.8s ease-out; }
    </style>
</x-guest-layout>
