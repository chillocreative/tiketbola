<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
});
</script>

<template>
    <Head title="Papan Pemuka" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-bold text-white">Papan Pemuka</h2>
        </template>

        <div class="p-4 sm:p-6 lg:p-8">
            <!-- Stats Cards -->
            <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <!-- Total -->
                <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-6 backdrop-blur-sm transition hover:border-white/10">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-[#00B4D8]/10 blur-2xl transition group-hover:bg-[#00B4D8]/20"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#00B4D8]/10">
                                <svg class="h-5 w-5 text-[#00B4D8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-400">Jumlah Pendaftaran</span>
                        </div>
                        <div class="mt-4 text-4xl font-extrabold text-white">{{ stats?.total || 0 }}</div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-6 backdrop-blur-sm transition hover:border-white/10">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-yellow-400/10 blur-2xl transition group-hover:bg-yellow-400/20"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-400/10">
                                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-400">Menunggu Pengesahan</span>
                        </div>
                        <div class="mt-4 text-4xl font-extrabold text-yellow-400">{{ stats?.pending || 0 }}</div>
                    </div>
                </div>

                <!-- Verified -->
                <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-6 backdrop-blur-sm transition hover:border-white/10">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-green-500/10 blur-2xl transition group-hover:bg-green-500/20"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-500/10">
                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-400">Telah Disahkan</span>
                        </div>
                        <div class="mt-4 text-4xl font-extrabold text-green-400">{{ stats?.verified || 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="rounded-2xl border border-white/5 bg-white/5 p-6 backdrop-blur-sm">
                <h3 class="mb-4 text-lg font-bold text-white">Tindakan Pantas</h3>
                <div class="flex flex-wrap gap-3">
                    <Link
                        :href="route('admin.submissions')"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-yellow-400 to-yellow-500 px-5 py-2.5 text-sm font-bold text-gray-900 shadow-lg shadow-yellow-500/20 transition hover:shadow-yellow-500/30"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Lihat Pendaftaran
                    </Link>
                    <Link
                        :href="route('admin.sendora.edit')"
                        class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-5 py-2.5 text-sm font-semibold text-gray-300 transition hover:bg-white/10 hover:text-white"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.573-1.066z" />
                        </svg>
                        Tetapan Sendora
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
