<nav class="sticky top-0 z-50 w-full bg-white border-b border-gray-200 shadow-sm">
  <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
    <!-- Left: Logo -->
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
      <img src="{{ asset('images/repairo.png') }}" alt="Repairo Logo" class="h-8 w-auto">
      <span class="text-xl font-semibold text-blue-600">Repairo</span>
    </a>

    <!-- Center: Nav Links -->
    <div class="hidden md:flex items-center space-x-8">
      <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 transition font-medium {{ request()->routeIs('home') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : '' }}">Home</a>
      <a href="{{ route('tracking') }}" class="text-gray-700 hover:text-blue-600 transition font-medium {{ request()->routeIs('tracking') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : '' }}">Tracking</a>
      <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition font-medium {{ request()->routeIs('dashboard') ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : '' }}">Dashboard</a>
    </div>

    <!-- Right: User -->
    <div class="hidden md:flex items-center space-x-4">
      @auth
      <div class="relative group">
        <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition font-medium focus:outline-none">
          <span>{{ Auth::user()->name }}</span>
          <svg class="h-4 w-4 text-gray-500 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div class="absolute right-0 hidden w-40 mt-2 bg-white border border-gray-100 rounded-lg shadow-md group-hover:block">
          <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Profile</a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Log Out</button>
          </form>
        </div>
      </div>
      @else
      <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium">Login</a>
      @endauth
    </div>

    <!-- Mobile Button -->
    <div class="md:hidden flex items-center">
      <button @click="open = !open" class="text-gray-700 hover:text-blue-600 focus:outline-none">
        <svg x-show="!open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg x-show="open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div x-show="open" x-transition class="md:hidden border-t border-gray-100 bg-white">
    <div class="px-4 py-3 space-y-2">
      <a href="{{ route('home') }}" class="block text-gray-700 font-medium hover:text-blue-600 {{ request()->routeIs('home') ? 'text-blue-600' : '' }}">Home</a>
      <a href="{{ route('tracking') }}" class="block text-gray-700 font-medium hover:text-blue-600 {{ request()->routeIs('tracking') ? 'text-blue-600' : '' }}">Tracking</a>
      <a href="{{ route('dashboard') }}" class="block text-gray-700 font-medium hover:text-blue-600 {{ request()->routeIs('dashboard') ? 'text-blue-600' : '' }}">Dashboard</a>
      <hr class="border-gray-200">
      @auth
      <a href="{{ route('profile.show') }}" class="block text-gray-700 font-medium hover:text-blue-600">Profile</a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left text-gray-700 font-medium hover:text-blue-600">Log Out</button>
      </form>
      @else
      <a href="{{ route('login') }}" class="block text-gray-700 font-medium hover:text-blue-600">Login</a>
      @endauth
    </div>
  </div>
</nav>
