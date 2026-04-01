<script setup>
import SoccerBall from '@/Components/SoccerBall.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-[#0B1A2E] px-4 pt-16 sm:justify-center sm:pt-0">
        <!-- Background glow effects -->
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -top-40 -left-40 h-80 w-80 rounded-full bg-yellow-400/5 blur-3xl"></div>
            <div class="absolute -bottom-40 -right-40 h-80 w-80 rounded-full bg-green-500/5 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 h-60 w-60 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#00B4D8]/5 blur-3xl"></div>
        </div>

        <!-- Logo -->
        <div class="relative z-10 mb-6">
            <Link href="/">
                <SoccerBall :size="64" />
            </Link>
        </div>

        <!-- Card -->
        <div class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl border border-white/10 bg-white/5 px-6 py-8 shadow-2xl backdrop-blur-sm sm:px-8">
            <slot />
        </div>

        <!-- Footer -->
        <div class="relative z-10 mt-6 pb-8 text-center">
            <Link href="/" class="text-xs text-gray-500 transition hover:text-yellow-400">
                &larr; Kembali ke halaman utama
            </Link>
        </div>
    </div>
</template>
