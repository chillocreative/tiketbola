<script setup>
import { ref } from 'vue';
import SoccerBall from '@/Components/SoccerBall.vue';
import { Link, router } from '@inertiajs/vue3';

const mobileMenuOpen = ref(false);

const navItems = [
    {
        label: 'Papan Pemuka',
        route: 'dashboard',
        icon: 'dashboard',
    },
    {
        label: 'Pendaftaran',
        route: 'admin.submissions',
        icon: 'list',
    },
    {
        label: 'Tetapan Sendora',
        route: 'admin.sendora.edit',
        icon: 'settings',
        match: 'admin.sendora.*',
    },
    {
        label: 'Profil',
        route: 'profile.edit',
        icon: 'user',
    },
];

const isActive = (item) => {
    if (item.match) return route().current(item.match);
    return route().current(item.route);
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-[#0B1A2E]">

        <!-- ==================== DESKTOP SIDEBAR ==================== -->
        <aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col border-r border-white/5 bg-[#071222] lg:flex">
            <!-- Logo area -->
            <div class="flex h-20 items-center gap-3 px-6">
                <SoccerBall :size="36" />
                <span class="text-lg font-extrabold tracking-tight text-white">
                    Tiket<span class="text-yellow-400">Bola</span>
                </span>
            </div>

            <!-- Nav links -->
            <nav class="flex-1 space-y-1 px-3 pt-4">
                <Link
                    v-for="item in navItems"
                    :key="item.route"
                    :href="route(item.route)"
                    :class="[
                        isActive(item)
                            ? 'bg-yellow-400/10 text-yellow-400'
                            : 'text-gray-400 hover:bg-white/5 hover:text-white',
                    ]"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition"
                >
                    <!-- Dashboard -->
                    <svg v-if="item.icon === 'dashboard'" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zm-10 9a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zm10-2a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1v-5z" />
                    </svg>
                    <!-- List -->
                    <svg v-if="item.icon === 'list'" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <!-- Settings -->
                    <svg v-if="item.icon === 'settings'" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.573-1.066z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <!-- User -->
                    <svg v-if="item.icon === 'user'" class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ item.label }}
                </Link>
            </nav>

            <!-- User / Logout -->
            <div class="border-t border-white/5 p-4">
                <div class="mb-3 px-2">
                    <p class="truncate text-sm font-semibold text-white">{{ $page.props.auth.user.name }}</p>
                    <p class="truncate text-xs text-gray-500">{{ $page.props.auth.user.email }}</p>
                </div>
                <button
                    @click="logout"
                    class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-red-400 transition hover:bg-red-500/10"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Log Keluar
                </button>
            </div>
        </aside>

        <!-- ==================== MOBILE TOP BAR ==================== -->
        <div class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-white/5 bg-[#071222]/95 px-4 backdrop-blur-sm lg:hidden">
            <div class="flex items-center gap-2">
                <SoccerBall :size="28" />
                <span class="text-base font-extrabold text-white">
                    Tiket<span class="text-yellow-400">Bola</span>
                </span>
            </div>
            <button
                @click="mobileMenuOpen = true"
                class="rounded-lg p-2 text-gray-400 transition hover:bg-white/10 hover:text-white"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- ==================== MOBILE FULL-SCREEN MENU ==================== -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="mobileMenuOpen" class="fixed inset-0 z-50 flex flex-col bg-[#071222]">
                    <!-- Mobile menu header -->
                    <div class="flex h-16 items-center justify-between px-5">
                        <div class="flex items-center gap-2">
                            <SoccerBall :size="28" />
                            <span class="text-base font-extrabold text-white">
                                Tiket<span class="text-yellow-400">Bola</span>
                            </span>
                        </div>
                        <button
                            @click="mobileMenuOpen = false"
                            class="rounded-lg p-2 text-gray-400 transition hover:bg-white/10 hover:text-white"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile nav links -->
                    <nav class="flex-1 space-y-2 px-5 pt-6">
                        <Link
                            v-for="item in navItems"
                            :key="item.route"
                            :href="route(item.route)"
                            @click="mobileMenuOpen = false"
                            :class="[
                                isActive(item)
                                    ? 'bg-yellow-400/10 text-yellow-400'
                                    : 'text-gray-300 hover:bg-white/5 hover:text-white',
                            ]"
                            class="flex items-center gap-4 rounded-2xl px-5 py-4 text-base font-semibold transition"
                        >
                            <svg v-if="item.icon === 'dashboard'" class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zm-10 9a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zm10-2a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1v-5z" />
                            </svg>
                            <svg v-if="item.icon === 'list'" class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <svg v-if="item.icon === 'settings'" class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.573-1.066z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg v-if="item.icon === 'user'" class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ item.label }}
                        </Link>
                    </nav>

                    <!-- Mobile user + logout -->
                    <div class="border-t border-white/5 px-5 py-5">
                        <div class="mb-4 px-2">
                            <p class="text-base font-semibold text-white">{{ $page.props.auth.user.name }}</p>
                            <p class="text-sm text-gray-500">{{ $page.props.auth.user.email }}</p>
                        </div>
                        <button
                            @click="logout"
                            class="flex w-full items-center gap-4 rounded-2xl px-5 py-4 text-base font-semibold text-red-400 transition hover:bg-red-500/10"
                        >
                            <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Log Keluar
                        </button>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- ==================== MAIN CONTENT ==================== -->
        <div class="lg:pl-64">
            <!-- Page Heading -->
            <header v-if="$slots.header" class="border-b border-white/5 bg-[#071222]/50 backdrop-blur-sm">
                <div class="px-4 py-5 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="min-h-[calc(100vh-4rem)]">
                <slot />
            </main>
        </div>
    </div>
</template>
