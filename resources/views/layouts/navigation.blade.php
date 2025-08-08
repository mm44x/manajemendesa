<nav x-data="{ open: false }"
    class="bg-white border-b border-gray-100 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-white" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @php $role = auth()->user()->role; @endphp
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if ($role !== 'bendahara')
                        <x-nav-link :href="route('kartu-keluarga.index')" :active="request()->routeIs('kartu-keluarga.*') || request()->routeIs('anggota-keluarga.*')">
                            {{ __('Manajemen KK') }}
                        </x-nav-link>
                    @endif
                    @if ($role == 'bendahara')
                        <x-nav-link :href="route('iuran.index')" :active="request()->routeIs('iuran.*')">
                            {{ __('Manajemen Iuran') }}
                        </x-nav-link>
                    @endif
                    <x-nav-link :href="route('semua-warga.index')" :active="request()->routeIs('semua-warga.*')">
                        {{ __('Data Semua Warga') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Tombol Dark Mode -->
                <div x-data="{ dark: localStorage.getItem('theme') === 'dark' }" x-init="$watch('dark', val => {
                    localStorage.setItem('theme', val ? 'dark' : 'light');
                    document.documentElement.classList.toggle('dark', val);
                })" class="mr-4">
                    <button @click="dark = !dark"
                        class="w-8 h-8 rounded-full flex items-center justify-center border border-gray-400 dark:border-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <template x-if="dark"><span class="text-white">🌙</span></template>
                        <template x-if="!dark"><span class="text-yellow-500">☀️</span></template>
                    </button>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-white bg-white dark:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-300 focus:outline-none transition">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Menu -->
    <div :class="{ 'block': open, 'hidden': !open }"
        class="hidden sm:hidden bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if ($role !== 'bendahara')
                <x-responsive-nav-link :href="route('kartu-keluarga.index')" :active="request()->routeIs('kartu-keluarga.*')">
                    {{ __('Manajemen KK') }}
                </x-responsive-nav-link>
            @endif
            @if ($role == 'bendahara')
                <x-responsive-nav-link :href="route('iuran.index')" :active="request()->routeIs('iuran.*')">
                    {{ __('Manajemen Iuran') }}
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('semua-warga.index')" :active="request()->routeIs('semua-warga.*')">
                {{ __('Data Semua Warga') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-300">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
