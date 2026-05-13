<nav x-data="{ open: false }" class="border-b border-white/10 bg-slate-950/80 backdrop-blur">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ tenant() ? route('tenant.dashboard', tenant()) : route('admin.dashboard') }}" class="text-lg font-semibold text-white">
                        {{ tenant() ? tenant()->name : 'Admin Panel' }}
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="tenant() ? route('tenant.dashboard', tenant()) : route('admin.dashboard')" :active="request()->routeIs('tenant.dashboard') || request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if (tenant())
                        <x-nav-link :href="route('tenant.projects.index', tenant())" :active="request()->routeIs('tenant.projects.*')">
                            {{ __('Projects') }}
                        </x-nav-link>
                        <x-nav-link :href="route('tenant.tasks.index', tenant())" :active="request()->routeIs('tenant.tasks.*')">
                            {{ __('Tasks') }}
                        </x-nav-link>
                        <x-nav-link :href="route('tenant.users.index', tenant())" :active="request()->routeIs('tenant.users.*')">
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('tenant.subscription.show', tenant())" :active="request()->routeIs('tenant.subscription.*')">
                            {{ __('Billing') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center rounded-md border border-white/10 bg-white/5 px-3 py-2 text-sm font-medium leading-4 text-slate-200 transition hover:text-white">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ tenant() ? route('tenant.logout', tenant()) : route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="tenant() ? route('tenant.logout', tenant()) : route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-slate-400 transition hover:bg-white/5 hover:text-white">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="tenant() ? route('tenant.dashboard', tenant()) : route('admin.dashboard')" :active="request()->routeIs('tenant.dashboard') || request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ tenant() ? route('tenant.logout', tenant()) : route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="tenant() ? route('tenant.logout', tenant()) : route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
