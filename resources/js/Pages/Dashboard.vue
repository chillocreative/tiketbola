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
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Papan Pemuka
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-6">
                    <div class="overflow-hidden rounded-lg bg-white p-6 shadow-sm">
                        <div class="text-sm font-medium text-gray-500">Jumlah Pendaftaran</div>
                        <div class="mt-1 text-3xl font-bold text-gray-900">{{ stats?.total || 0 }}</div>
                    </div>
                    <div class="overflow-hidden rounded-lg bg-white p-6 shadow-sm">
                        <div class="text-sm font-medium text-gray-500">Menunggu Pengesahan</div>
                        <div class="mt-1 text-3xl font-bold text-yellow-600">{{ stats?.pending || 0 }}</div>
                    </div>
                    <div class="overflow-hidden rounded-lg bg-white p-6 shadow-sm">
                        <div class="text-sm font-medium text-gray-500">Telah Disahkan</div>
                        <div class="mt-1 text-3xl font-bold text-green-600">{{ stats?.verified || 0 }}</div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Tindakan Pantas</h3>
                        <div class="flex flex-wrap gap-4">
                            <Link
                                :href="route('admin.submissions')"
                                class="rounded-lg bg-[#0B1A2E] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#162d4a]"
                            >
                                Lihat Pendaftaran
                            </Link>
                            <Link
                                :href="route('admin.sendora.edit')"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                            >
                                Tetapan Sendora
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
