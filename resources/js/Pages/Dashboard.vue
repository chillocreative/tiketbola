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

            <!-- AMK Card -->
            <div class="mb-6">
                <div class="mb-3 flex items-center gap-2">
                    <div class="h-3 w-3 rounded-full bg-yellow-400"></div>
                    <h3 class="text-sm font-bold text-yellow-400 uppercase tracking-wider">AMK & JBPP Pinang Tunggal</h3>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-5 sm:gap-4">
                    <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-5 backdrop-blur-sm transition hover:border-yellow-400/20">
                        <div class="relative">
                            <span class="text-xs font-medium text-gray-500">Jumlah</span>
                            <div class="mt-1 text-3xl font-extrabold text-white">{{ stats?.amk?.total || 0 }}</div>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-5 backdrop-blur-sm transition hover:border-yellow-400/20">
                        <div class="relative">
                            <span class="text-xs font-medium text-gray-500">Menunggu</span>
                            <div class="mt-1 text-3xl font-extrabold text-yellow-400">{{ stats?.amk?.pending || 0 }}</div>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-5 backdrop-blur-sm transition hover:border-green-500/20">
                        <div class="relative">
                            <span class="text-xs font-medium text-gray-500">Diluluskan</span>
                            <div class="mt-1 text-3xl font-extrabold text-green-400">{{ stats?.amk?.verified || 0 }}</div>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-5 backdrop-blur-sm transition hover:border-blue-500/20">
                        <div class="relative">
                            <span class="text-xs font-medium text-gray-500">Tiket Diambil</span>
                            <div class="mt-1 text-3xl font-extrabold text-blue-400">{{ stats?.amk?.issued || 0 }}</div>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-5 backdrop-blur-sm transition hover:border-red-500/20">
                        <div class="relative">
                            <span class="text-xs font-medium text-gray-500">Ditolak</span>
                            <div class="mt-1 text-3xl font-extrabold text-red-400">{{ stats?.amk?.rejected || 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MBSP Card -->
            <div class="mb-8">
                <div class="mb-3 flex items-center gap-2">
                    <div class="h-3 w-3 rounded-full bg-[#00B4D8]"></div>
                    <h3 class="text-sm font-bold text-[#00B4D8] uppercase tracking-wider">MBSP & KADUN Pinang Tunggal</h3>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-5 sm:gap-4">
                    <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-5 backdrop-blur-sm transition hover:border-[#00B4D8]/20">
                        <div class="relative">
                            <span class="text-xs font-medium text-gray-500">Jumlah</span>
                            <div class="mt-1 text-3xl font-extrabold text-white">{{ stats?.mbsp?.total || 0 }}</div>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-5 backdrop-blur-sm transition hover:border-yellow-400/20">
                        <div class="relative">
                            <span class="text-xs font-medium text-gray-500">Menunggu</span>
                            <div class="mt-1 text-3xl font-extrabold text-yellow-400">{{ stats?.mbsp?.pending || 0 }}</div>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-5 backdrop-blur-sm transition hover:border-green-500/20">
                        <div class="relative">
                            <span class="text-xs font-medium text-gray-500">Diluluskan</span>
                            <div class="mt-1 text-3xl font-extrabold text-green-400">{{ stats?.mbsp?.verified || 0 }}</div>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-5 backdrop-blur-sm transition hover:border-blue-500/20">
                        <div class="relative">
                            <span class="text-xs font-medium text-gray-500">Tiket Diambil</span>
                            <div class="mt-1 text-3xl font-extrabold text-blue-400">{{ stats?.mbsp?.issued || 0 }}</div>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-2xl border border-white/5 bg-white/5 p-5 backdrop-blur-sm transition hover:border-red-500/20">
                        <div class="relative">
                            <span class="text-xs font-medium text-gray-500">Ditolak</span>
                            <div class="mt-1 text-3xl font-extrabold text-red-400">{{ stats?.mbsp?.rejected || 0 }}</div>
                        </div>
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
