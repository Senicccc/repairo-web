<nav x-data="{ open: false }" class="bg-white shadow-sm sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Left: Logo -->
      <div class="flex items-center space-x-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
          <!-- Logo PNG (put repairo.png in public/images/) -->
          <img src="{{ asset('images/repairo.png') }}" alt="Repairo" class="h-9 w-auto object-contain" />
          <span class="text-blue-600 font-semibold text-lg tracking-tight select-none">Repairo</span>
        </a>
      </div>

      <!-- Center: Nav Links (desktop) -->
      <div class="hidden sm:flex sm:items-center sm:space-x-6">
        <a href="{{ route('home') }}"
           class="nav-link"
           :class="{ 'active': {{ request()->routeIs('home') ? 'true' : 'false' }} }"
           aria-current="{{ request()->routeIs('home') ? 'page' : '' }}">
           Home
        </a>

        <a href="{{ route('tracking') }}"
           class="nav-link"
           :class="{ 'active': {{ request()->routeIs('tracking') ? 'true' : 'false' }} }"
           aria-current="{{ request()->routeIs('tracking') ? 'page' : '' }}">
           Tracking
        </a>

        <a href="{{ route('dashboard') }}"
           class="nav-link"
           :class="{ 'active': {{ request()->routeIs('dashboard') ? 'true' : 'false' }} }"
           aria-current="{{ request()->routeIs('dashboard') ? 'page' : '' }}">
           Dashboard
        </a>
      </div>

      <!-- Right: User area -->
      <div class="flex items-center space-x-4">
        <!-- Desktop dropdown -->
        <div class="hidden sm:flex sm:items-center">
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button class="flex items-center gap-3 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
                <!-- Simple avatar: if you have user avatar, replace with <img> -->
                <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-medium">
                  {{ Auth::user() ? strtoupper(substr(Auth::user()->name,0,1)) : 'G' }}
                </div>
                <div class="text-left">
                  <div class="text-sm font-medium text-gray-800 leading-4">{{ Auth::user() ? Auth::user()->name : 'Guest' }}</div>
                  <div class="text-xs text-gray-500 leading-4">{{ Auth::user() ? Auth::user()->email : '' }}</div>
                </div>
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>
            </x-slot>

            <x-slot name="content">
              <x-dropdown-link :href="route('profile.show')">
                Profile
              </x-dropdown-link>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                  Log Out
                </x-dropdown-link>
              </form>
            </x-slot>
          </x-dropdown>
        </div>

        <!-- Mobile hamburger -->
        <div class="sm:hidden">
          <button @click="open = !open" aria-label="Toggle menu"
                  class="p-2 rounded-md inline-flex items-center justify-center text-gray-600 hover:text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
            <svg x-show="!open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="open" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div x-show="open" x-collapse class="sm:hidden border-t border-gray-100 bg-white">
    <div class="px-4 pt-4 pb-3 space-y-1">
      <a href="{{ route('home') }}" class="mobile-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
      <a href="{{ route('tracking') }}" class="mobile-link {{ request()->routeIs('tracking') ? 'active' : '' }}">Tracking</a>
      <a href="{{ route('dashboard') }}" class="mobile-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>

      <div class="pt-3 border-t border-gray-100">
        <a href="{{ route('profile.show') }}" class="mobile-link">Profile</a>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="w-full text-left mobile-link">Log Out</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Inline small CSS for polish -->
  <style>
    /* Desktop nav link */
    .nav-link{
      @apply text-sm text-gray-700 px-2 py-2 rounded-md relative inline-flex items-center transition;
      text-decoration: none;
      --tw-text-opacity: 1;
    }
    .nav-link::after{
      content: "";
      position: absolute;
      left: 10%;
      right: 10%;
      bottom: 0.25rem;
      height: 2px;
      background-color: transparent;
      transition: all .18s ease-in-out;
      border-radius: 2px;
    }
    .nav-link:hover::after{
      background-color: #2563eb; /* blue-600 */
      left: 8%;
      right: 8%;
    }
    .nav-link.active, .nav-link[aria-current="page"]{
      color: #2563eb; /* blue-600 */
      font-weight: 600;
    }
    .nav-link.active::after, .nav-link[aria-current="page"]::after{
      background-color: #2563eb;
      left: 8%;
      right: 8%;
    }

    /* Mobile links */
    .mobile-link{
      @apply block text-sm text-gray-700 px-3 py-2 rounded-md hover:bg-blue-50 hover:text-blue-600 transition;
      text-decoration: none;
    }
    .mobile-link.active{
      @apply bg-blue-50 text-blue-600 font-medium;
    }

    /* Small accessibility tweak for focus visible */
    button:focus, a:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
      border-radius: .375rem;
    }
  </style>
</nav>
