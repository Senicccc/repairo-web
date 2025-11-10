<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white shadow-sm">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
      
      <!-- Left: Logo -->
      <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
        <img src="{{ asset('images/repairo.png') }}" alt="Repairo Web" class="h-9 w-auto object-contain transition-opacity duration-300 group-hover:opacity-80" />
        <span class="text-lg font-semibold tracking-tight select-none" style="color:#1800AD;">Repairo Web</span>
      </a>

      <!-- Center: Nav Links -->
      <div class="hidden sm:flex items-center space-x-6">
        <a href="{{ route('home') }}" class="relative text-sm font-medium text-gray-700 hover:text-[##1800AD] transition 
          after:absolute after:left-1/2 after:-translate-x-1/2 after:bottom-0 after:h-[2px] after:w-0 hover:after:w-3/5 after:bg-[#1800AD] after:transition-all
          {{ request()->routeIs('home') ? 'text-[#1800AD] after:w-3/5' : '' }}">
          Home
        </a>

        <a href="{{ route('tracking') }}" class="relative text-sm font-medium text-gray-700 hover:text-[#1800AD] transition 
          after:absolute after:left-1/2 after:-translate-x-1/2 after:bottom-0 after:h-[2px] after:w-0 hover:after:w-3/5 after:bg-[#1800AD] after:transition-all
          {{ request()->routeIs('tracking') ? 'text-[#1800AD] after:w-3/5' : '' }}">
          Tracking
        </a>

        <a href="{{ route('dashboard') }}" class="relative text-sm font-medium text-gray-700 hover:text-[#1800AD] transition 
          after:absolute after:left-1/2 after:-translate-x-1/2 after:bottom-0 after:h-[2px] after:w-0 hover:after:w-3/5 after:bg-[#1800AD] after:transition-all
          {{ request()->routeIs('dashboard') ? 'text-[#1800AD] after:w-3/5' : '' }}">
          Dashboard
        </a>
      </div>

      <!-- Right: User / Mobile -->
      <div class="flex items-center space-x-4">
        @auth
        <!-- Desktop Dropdown -->
        <div class="hidden sm:flex items-center">
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button class="flex items-center gap-3 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1800AD] transition">
                <div class="h-8 w-8 rounded-full flex items-center justify-center font-medium" style="background-color:#f5f3ff; color:#1800AD;">
                  {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="text-left">
                  <p class="text-sm font-medium text-gray-800 leading-4">{{ Auth::user()->name }}</p>
                  <p class="text-xs text-gray-500 leading-4">{{ Auth::user()->email }}</p>
                </div>
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
            </x-slot>

            <x-slot name="content">
              <x-dropdown-link :href="route('profile.show')">Profile</x-dropdown-link>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                  Log Out
                </x-dropdown-link>
              </form>
            </x-slot>
          </x-dropdown>
        </div>
        @endauth

        <!-- Mobile Menu Button -->
        <div class="sm:hidden">
          <button @click="open = !open" aria-label="Toggle menu"
                  class="p-2 rounded-md inline-flex items-center justify-center text-gray-600 hover:text-[#1800AD] hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-[#1800AD] transition">
            <svg x-show="!open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div x-show="open" x-collapse class="sm:hidden border-t border-gray-100 bg-white">
    <div class="px-4 pt-4 pb-3 space-y-1">
      <a href="{{ route('home') }}" class="block text-sm px-3 py-2 rounded-md transition 
         hover:bg-indigo-50 hover:text-[#1800AD] {{ request()->routeIs('home') ? 'bg-indigo-50 text-[#1800AD] font-medium' : 'text-gray-700' }}">
        Home
      </a>
      <a href="{{ route('tracking') }}" class="block text-sm px-3 py-2 rounded-md transition 
         hover:bg-indigo-50 hover:text-[#1800AD] {{ request()->routeIs('tracking') ? 'bg-indigo-50 text-[#1800AD] font-medium' : 'text-gray-700' }}">
        Tracking
      </a>
      <a href="{{ route('dashboard') }}" class="block text-sm px-3 py-2 rounded-md transition 
         hover:bg-indigo-50 hover:text-[#1800AD] {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-[#1800AD] font-medium' : 'text-gray-700' }}">
        Dashboard
      </a>
      <div class="pt-3 border-t border-gray-100">
        <a href="{{ route('profile.show') }}" class="block text-sm px-3 py-2 rounded-md text-gray-700 hover:text-[#1800AD] hover:bg-indigo-50 transition">
          Profile
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="block w-full text-left text-sm px-3 py-2 rounded-md text-gray-700 hover:text-[#1800AD] hover:bg-indigo-50 transition">
            Log Out
          </button>
        </form>
      </div>
    </div>
  </div>
</nav>
