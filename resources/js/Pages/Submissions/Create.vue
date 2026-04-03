<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    quotas: Object,
});

const showFullModal = ref(false);
</script>

<template>
    <Head title="Tiket Bola Percuma Pulau Pinang vs. Sabah" />

    <div class="min-h-screen bg-[#0B1A2E]">
        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <!-- Background glow effects -->
            <div class="absolute -top-40 -left-40 h-80 w-80 rounded-full bg-yellow-400/10 blur-3xl"></div>
            <div class="absolute -top-20 -right-20 h-60 w-60 rounded-full bg-green-500/10 blur-3xl"></div>

            <div class="mx-auto max-w-lg px-4 pt-6 sm:px-6 lg:max-w-2xl">
                <!-- Title -->
                <h1 class="mb-2 text-center text-2xl font-extrabold leading-tight text-white sm:text-3xl lg:text-4xl">
                    Tiket Bola Percuma
                </h1>
                <h2 class="mb-5 text-center text-xl font-bold sm:text-2xl lg:text-3xl">
                    <span class="text-[#00B4D8]">Pulau Pinang</span>
                    <span class="mx-2 text-yellow-400">vs.</span>
                    <span class="text-[#E63946]">Sabah</span>
                </h2>

                <!-- Match Info Pills -->
                <div class="mb-5 flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                    <div class="flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 backdrop-blur-sm">
                        <svg class="h-4 w-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-xs font-medium text-gray-200">04 April 2026</span>
                    </div>
                    <div class="flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 backdrop-blur-sm">
                        <svg class="h-4 w-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs font-medium text-gray-200">9:00 Malam</span>
                    </div>
                    <div class="flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 backdrop-blur-sm">
                        <svg class="h-4 w-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-xs font-medium text-gray-200">Stadium Bandaraya</span>
                    </div>
                </div>

                <!-- Poster Image -->
                <div class="relative mx-auto max-w-md">
                    <div class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-yellow-400 via-green-500 to-[#00B4D8] opacity-50 blur-sm"></div>
                    <img
                        src="/images/poster-bola.jpeg"
                        alt="Poster Tiket Bola Percuma Pulau Pinang vs Sabah"
                        class="relative w-full rounded-2xl shadow-2xl"
                    />
                </div>

                <!-- Tickets remaining badge -->
                <div class="mt-5 flex justify-center">
                    <div class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-yellow-500 to-yellow-600 px-5 py-2.5 shadow-lg shadow-yellow-500/25">
                        <svg class="h-5 w-5 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z" />
                        </svg>
                        <span class="text-sm font-extrabold text-yellow-900">150 TIKET PERCUMA</span>
                    </div>
                </div>

                <!-- Syarat Kelayakan -->
                <div class="mx-auto mt-6 max-w-sm rounded-2xl border border-white/10 bg-white/5 px-5 py-4 backdrop-blur-sm">
                    <p class="mb-3 text-center text-xs font-bold uppercase tracking-widest text-yellow-400">Syarat Kelayakan</p>
                    <div class="space-y-2">
                        <div class="flex items-start gap-2.5">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-gray-300">Terbuka kepada individu berumur 18 tahun dan ke atas yang merupakan pengundi Parlimen Kepala Batas.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-gray-300">Penyertaan juga dibuka kepada individu berumur 17 tahun, dengan syarat alamat pada kad pengenalan adalah dalam Parlimen Kepala Batas.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-gray-300">Setiap individu hanya layak menuntut satu (1) tiket sahaja.</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-gray-300">Peserta diwajibkan untuk like, komen dan share posting tajaan tiket, serta meninggalkan komen dengan hashtag #HariaPenangHaria.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Buttons Section -->
        <div class="relative mt-8 pb-12">
            <!-- Decorative divider -->
            <div class="absolute inset-x-0 top-0 flex justify-center">
                <div class="h-px w-2/3 bg-gradient-to-r from-transparent via-yellow-400/50 to-transparent"></div>
            </div>

            <div class="mx-auto max-w-lg px-4 pt-8 sm:px-6 lg:max-w-xl">
                <h3 class="mb-6 text-center text-xl font-bold text-white sm:text-2xl">Daftar Sekarang</h3>

                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <!-- Button 1: AMK -->
                    <div class="overflow-hidden rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm transition hover:border-yellow-400/30">
                        <div class="flex h-full flex-col p-4 sm:p-5">
                            <Link
                                v-if="quotas.amk.balance > 0"
                                :href="route('submissions.form', 'amk')"
                                class="block w-full rounded-xl bg-gradient-to-r from-yellow-400 to-yellow-500 px-4 py-3 text-center text-xs font-extrabold text-gray-900 shadow-lg shadow-yellow-400/25 transition-all hover:from-yellow-300 hover:to-yellow-400 hover:shadow-yellow-400/40 sm:px-6 sm:py-3.5 sm:text-sm"
                            >
                                Daftar Sekarang
                            </Link>
                            <button
                                v-else
                                @click="showFullModal = true"
                                class="w-full rounded-xl bg-white/10 px-4 py-3 text-center text-xs font-bold text-gray-500 transition hover:bg-white/15 sm:px-6 sm:py-3.5 sm:text-sm"
                            >
                                Kuota Penuh
                            </button>
                            <p class="mt-3 text-center text-[10px] leading-relaxed text-gray-400 sm:text-xs">
                                Tajaan Angkatan Muda Keadilan Cabang Kepala Batas & JBPP Pinang Tunggal
                            </p>
                            <div class="mt-2 text-center">
                                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-400/10 px-2.5 py-1 text-[10px] font-bold text-yellow-400 sm:text-xs">
                                    Baki: {{ quotas.amk.balance }} / {{ quotas.amk.total }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Button 2: MBSP -->
                    <div class="overflow-hidden rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm transition hover:border-[#00B4D8]/30">
                        <div class="flex h-full flex-col p-4 sm:p-5">
                            <Link
                                v-if="quotas.mbsp.balance > 0"
                                :href="route('submissions.form', 'mbsp')"
                                class="block w-full rounded-xl bg-gradient-to-r from-[#00B4D8] to-[#0096c7] px-4 py-3 text-center text-xs font-extrabold text-white shadow-lg shadow-[#00B4D8]/25 transition-all hover:from-[#48cae4] hover:to-[#00B4D8] hover:shadow-[#00B4D8]/40 sm:px-6 sm:py-3.5 sm:text-sm"
                            >
                                Daftar Sekarang
                            </Link>
                            <button
                                v-else
                                @click="showFullModal = true"
                                class="w-full rounded-xl bg-white/10 px-4 py-3 text-center text-xs font-bold text-gray-500 transition hover:bg-white/15 sm:px-6 sm:py-3.5 sm:text-sm"
                            >
                                Kuota Penuh
                            </button>
                            <p class="mt-3 text-center text-[10px] leading-relaxed text-gray-400 sm:text-xs">
                                Tajaan Ahli Majlis MBSP, Pegawai Penyelaras KADUN Pinang Tunggal, Parti KEADILAN Cabang Kepala Batas
                            </p>
                            <div class="mt-2 text-center">
                                <span class="inline-flex items-center gap-1 rounded-full bg-[#00B4D8]/10 px-2.5 py-1 text-[10px] font-bold text-[#00B4D8] sm:text-xs">
                                    Baki: {{ quotas.mbsp.balance }} / {{ quotas.mbsp.total }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <a :href="route('login')" class="inline-block text-[10px] text-gray-600 transition hover:text-yellow-400">
                        Admin
                    </a>
                </div>
            </div>
        </div>
        <!-- Quota Full Modal -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="showFullModal" class="fixed inset-0 z-50 flex items-center justify-center px-4" @click.self="showFullModal = false">
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
                    <div class="relative w-full max-w-sm overflow-hidden rounded-2xl border border-white/10 bg-[#0f2340] p-6 shadow-2xl">
                        <!-- Icon -->
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-500/10">
                            <svg class="h-7 w-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-center text-lg font-bold text-white">Kuota Telah Penuh</h3>
                        <p class="mb-6 text-center text-sm leading-relaxed text-gray-400">
                            Maaf, kuota pendaftaran untuk kategori ini telah penuh. Terima kasih atas minat anda.
                        </p>
                        <button
                            @click="showFullModal = false"
                            class="w-full rounded-xl bg-gradient-to-r from-yellow-400 to-yellow-500 px-6 py-3 text-sm font-extrabold text-gray-900 transition hover:from-yellow-300 hover:to-yellow-400"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>
