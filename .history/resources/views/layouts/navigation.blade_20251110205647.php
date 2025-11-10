<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
      
      <!-- Left: Logo -->
      <a href="{{ route('dashboard') }}" class="flex items-center gap-3 select-none">
        <img src="{{ asset('images/repairo.png') }}" alt="Repairo" class="h-8 w-auto object-contain">
        <span class="text-blue-600 font-semibold text-lg tracking-tight">Repairo</span>
      </a>

      <!-- Center: Links -->
      <div class="hidden md:flex items-center gap-8">
        @php
          $links = [
            ['label' => 'Home', 'route' => 'home'],
            ['label' => 'Tracking', 'route' => 'tracking'],
            ['label' => 'Dashboard', 'route' => 'dashboard'],
          ];
        @endphp
        @foreach($links as $link)
          <a href="{{ route($link['route']) }}"
             class="relative text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200
                    {{ request()->routeIs($link['route']) ? 'text-blue-600 font-semibold' : '' }}">
            {{ $link['label'] }}
            <span class="absolute left-1/2 -bottom-1.5 h-0.5 w-0 bg-blue-600 rounded-full transition-all duration-300 
                        {{ request()->routeIs($link['route']) ? 'w-4 left-1/2 -translate-x-1/2' : 'group-hover:w-4 group-hover:-translate-x-1/2' }}"></span>
          </a>
        @endforeach
      </div>

      <!-- Right: Profile -->
      <div class="flex items-center gap-4">
        @auth
        <div class="relative group">
          <button class="flex items-center gap-2 px-2 py-1.5 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-300 transition">
            <div class="h-8 w-8 flex items-center justify-center rounded-full bg-blue-50 text-blue-600 font-semibold">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <svg class="h-4 w-4 text-gray-400 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div class="absolute right-0 mt-2 w-40 bg-white rounded-xl border border-gray-100 shadow-lg opacity-0 scale-95 origin-top-right 
                      group-hover:opacity-100 group-hover:scale-100 transition-all duration-200">
            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Log Out</button>
            </form>
          </div>
        </div>
        @else
        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Login</a>
        @endauth

        <!-- Mobile Toggle -->
        <button @click="open = !open" class="md:hidden p-2 rounded-md text-gray-600 hover:text-blue-600 hover:bg-blue-50 focus:ring-2 focus:ring-blue-300">
          <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
          <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div x-show="open" x-collapse class="md:hidden border-t border-gray-100 bg-white">
    <div class="px-4 pt-3 pb-4 space-y-1">
      @foreach($links as $link)
      <a href="{{ route($link['route']) }}"
         class="block px-3 py-2 text-sm rounded-md hover:bg-blue-50 hover:text-blue-600 transition
                {{ request()->routeIs($link['route']) ? 'text-blue-600 bg-blue-50 font-medium' : 'text-gray-700' }}">
         {{ $link['label'] }}
      </a>
      @endforeach
      <hr class="my-2 border-gray-200">
      @auth
      <a href="{{ route('profile.show') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Profile</a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Log Out</button>
      </form>
      @else
      <a href="{{ route('login') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">Login</a>
      @endauth
    </div>
  </div>
</nav>
