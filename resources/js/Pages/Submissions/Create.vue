<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    phone: '',
    email: '',
    message: '',
});

const submitted = ref(false);

const submit = () => {
    form.post(route('submissions.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            submitted.value = true;
            setTimeout(() => submitted.value = false, 6000);
        },
    });
};
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
            </div>
        </div>

        <!-- Form Section -->
        <div class="relative mt-8 pb-12">
            <!-- Decorative divider -->
            <div class="absolute inset-x-0 top-0 flex justify-center">
                <div class="h-px w-2/3 bg-gradient-to-r from-transparent via-yellow-400/50 to-transparent"></div>
            </div>

            <div class="mx-auto max-w-lg px-4 sm:px-6 lg:max-w-xl">
                <!-- Section Title -->
                <div class="mb-6 pt-6 text-center">
                    <h3 class="text-xl font-bold text-white sm:text-2xl">Daftar Sekarang</h3>
                    <p class="mt-1 text-sm text-gray-400">Isi borang di bawah untuk menuntut tiket percuma anda</p>
                </div>

                <!-- Success Message -->
                <div
                    v-if="submitted || $page.props.flash?.success"
                    class="mb-5 overflow-hidden rounded-xl border border-green-500/30 bg-green-500/10 backdrop-blur-sm"
                >
                    <div class="flex items-center gap-3 px-4 py-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-500">
                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-green-300">Berjaya dihantar!</p>
                            <p class="text-xs text-green-400/80">Pendaftaran anda telah diterima. Sila tunggu pengesahan.</p>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="overflow-hidden rounded-2xl border border-white/10 bg-white/5 shadow-2xl backdrop-blur-sm">
                    <div class="p-5 sm:p-7">
                        <form @submit.prevent="submit" class="space-y-5">
                            <!-- Name -->
                            <div>
                                <label for="name" class="mb-1.5 block text-sm font-semibold text-gray-200">
                                    Nama Penuh <span class="text-yellow-400">*</span>
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                    placeholder="Masukkan nama penuh anda"
                                />
                                <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-400">{{ form.errors.name }}</p>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="mb-1.5 block text-sm font-semibold text-gray-200">
                                    No. Telefon <span class="text-yellow-400">*</span>
                                </label>
                                <input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    required
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                    placeholder="cth: 0123456789"
                                />
                                <p v-if="form.errors.phone" class="mt-1.5 text-xs text-red-400">{{ form.errors.phone }}</p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-200">
                                    Emel <span class="text-yellow-400">*</span>
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                    placeholder="contoh@email.com"
                                />
                                <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400">{{ form.errors.email }}</p>
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="message" class="mb-1.5 block text-sm font-semibold text-gray-200">
                                    Mesej / Catatan <span class="text-yellow-400">*</span>
                                </label>
                                <textarea
                                    id="message"
                                    v-model="form.message"
                                    rows="3"
                                    required
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                    placeholder="Nyatakan bilangan tiket / sebarang catatan..."
                                ></textarea>
                                <p v-if="form.errors.message" class="mt-1.5 text-xs text-red-400">{{ form.errors.message }}</p>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="group relative w-full overflow-hidden rounded-xl bg-gradient-to-r from-yellow-400 to-yellow-500 px-6 py-3.5 text-sm font-extrabold text-gray-900 shadow-lg shadow-yellow-500/25 transition-all hover:from-yellow-300 hover:to-yellow-400 hover:shadow-yellow-500/40 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 focus:ring-offset-[#0B1A2E] disabled:opacity-50"
                            >
                                <span v-if="form.processing" class="flex items-center justify-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                    </svg>
                                    Menghantar...
                                </span>
                                <span v-else class="flex items-center justify-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5l-1 1m0 0l-7 7m7-7l1 1m-1-1L9 15m6-10l-2 2" />
                                    </svg>
                                    Hantar Pendaftaran
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Footer info -->
                <div class="mt-6 space-y-3 text-center">
                    <div class="flex flex-col items-center gap-1 sm:flex-row sm:justify-center sm:gap-4">
                        <a href="tel:+60164813577" class="inline-flex items-center gap-1.5 text-xs text-gray-400 transition hover:text-green-400">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.15 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                            </svg>
                            Zakiri: 016-481 3577
                        </a>
                        <a href="tel:+60135190613" class="inline-flex items-center gap-1.5 text-xs text-gray-400 transition hover:text-green-400">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.15 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                            </svg>
                            Hafiz: 013-519 0613
                        </a>
                    </div>
                    <p class="text-[10px] text-gray-600">Stadium Bandaraya, Pulau Pinang</p>
                    <a :href="route('login')" class="inline-block text-[10px] text-gray-600 transition hover:text-yellow-400">
                        Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
