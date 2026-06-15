<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-background font-sans antialiased text-foreground">
    <div x-data="{ mobileNavOpen: false }" class="min-h-screen md:flex">

        <div
            x-cloak
            x-show="mobileNavOpen"
            x-transition.opacity
            class="fixed inset-0 z-40 bg-stone-950/55 backdrop-blur-sm md:hidden"
            @click="mobileNavOpen = false"
        ></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-[86vw] max-w-[20rem] -translate-x-full flex-col overflow-y-auto border-r border-white/5 bg-stone-950 text-white transition-transform duration-300 md:static md:z-auto md:w-72 md:max-w-none md:translate-x-0"
            :class="mobileNavOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        >
            <div class="flex items-center justify-between border-b border-white/5 p-5 md:hidden">
                <div>
                    <p class="text-[10px] font-mono uppercase tracking-[0.26em] text-stone-500">Navigation</p>
                    <h2 class="mt-2 font-display text-xl font-semibold text-stone-100">WSL CRM</h2>
                </div>
                <button
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-stone-300"
                    @click="mobileNavOpen = false"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 p-5 md:p-6">
                <!-- Logo -->
                <div class="mb-8 flex items-center space-x-3 px-2 md:mb-10">
                    <div
                        class="w-10 h-10 bg-primary rounded-2xl flex items-center justify-center font-bold text-white shadow-lg shadow-primary/20">
                        <span class="font-display text-xl">W</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-display font-bold leading-none tracking-tight">WSL<span
                                class="text-primary">CRM</span></span>
                        <span class="text-[10px] font-mono text-stone-500 uppercase tracking-widest mt-1">Buyer
                            Readiness</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-1.5">
                    <div class="px-4 text-[10px] font-mono text-stone-500 uppercase tracking-[0.2em] mb-3">Main
                        Navigation</div>

                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span>Dashboard</span>
                    </x-nav-link>

                    <div class="px-4 text-[10px] font-mono text-stone-500 uppercase tracking-[0.2em] mt-8 mb-3">Sales &
                        CRM</div>
                    <x-nav-link href="{{ route('prospects.index') }}" :active="request()->routeIs('prospects.*')">
                        <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span>{{ Auth::user()->role === 'pimpinan' ? 'Data Pelanggan' : 'Data Prospek' }}</span>
                    </x-nav-link>

                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'pimpinan')
                        <div class="px-4 text-[10px] font-mono text-stone-500 uppercase tracking-[0.2em] mt-8 mb-3">
                            Administration</div>
                        @if(Auth::user()->role === 'admin')
                            <x-nav-link href="{{ route('criterias.index') }}" :active="request()->routeIs('criterias.*')">
                                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                                <span>Kriteria SPK</span>
                            </x-nav-link>
                        @endif
                        <x-nav-link href="{{ route('admin.sales.index') }}" :active="request()->routeIs('admin.sales.*')">
                            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>Data Sales</span>
                        </x-nav-link>
                    @endif

                    <div class="mt-10 pt-6 border-t border-white/5">
                        <x-nav-link href="{{ route('guide.index') }}" :active="request()->routeIs('guide.*')"
                            class="group">
                            <svg class="w-5 h-5 text-accent opacity-70" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                            <span>Panduan & SPK</span>
                        </x-nav-link>
                    </div>
                </nav>
            </div>

            <!-- User Profile (Bottom) -->
            <div class="p-4 border-t border-white/5 bg-black/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-sm font-bold text-primary">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-xs font-semibold text-stone-100 truncate w-24">{{ Auth::user()->name ?? 'Guest' }}</span>
                            <span
                                class="text-[10px] text-stone-500 font-mono uppercase tracking-tighter">{{ Auth::user()->role ?? 'Visitor' }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="p-2 rounded-lg text-stone-500 hover:text-red-400 hover:bg-red-400/10 transition-all duration-200"
                            title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex min-h-screen flex-1 flex-col md:max-h-screen">
            <!-- Header (Fixed Top) -->
            <header
                class="sticky top-0 z-30 flex min-h-16 items-center justify-between border-b border-border/50 bg-background/85 px-4 py-3 backdrop-blur-md md:px-8 lg:px-10">
                <div class="flex min-w-0 items-center space-x-3 md:space-x-4">
                    <button
                        type="button"
                        class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl border border-border/70 bg-white/70 text-foreground shadow-sm md:hidden"
                        @click="mobileNavOpen = true"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <p class="text-[10px] font-mono uppercase tracking-[0.24em] text-muted-foreground md:hidden">Workspace</p>
                        <h2 class="truncate font-display text-lg font-bold tracking-tight text-foreground md:text-xl">
                        @if (isset($header))
                            {{ $header }}
                        @else
                            Dashboard
                        @endif
                        </h2>
                    </div>
                </div>
                <!-- Actions -->
                <div class="flex items-center space-x-2 md:space-x-4">
                    <div class="hidden md:flex items-center space-x-2 mr-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-mono text-muted-foreground uppercase tracking-widest">System
                            Active</span>
                    </div>
                    <button
                        class="w-10 h-10 rounded-xl flex items-center justify-center text-muted-foreground hover:text-primary hover:bg-primary/5 transition-all duration-200 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <span
                            class="absolute top-2 right-2 w-2 h-2 bg-accent rounded-full border-2 border-background"></span>
                    </button>
                </div>
            </header>

            <!-- Page Content (Scrollable Container) -->
            <main class="flex-1 overflow-y-auto bg-background">
                <div class="mx-auto max-w-[1600px] px-4 pb-8 pt-5 sm:px-5 md:px-8 md:py-8 lg:px-10 lg:py-10">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>

</html>
