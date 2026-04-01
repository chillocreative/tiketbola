<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    category: String,
    categoryLabel: String,
});

const form = useForm({
    name: '',
    phone: '',
    email: '',
    message: '',
    category: props.category,
});

const submitted = ref(false);

const submit = () => {
    form.post(route('submissions.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('name', 'phone', 'email', 'message');
            submitted.value = true;
            setTimeout(() => submitted.value = false, 6000);
        },
    });
};

const accentColor = props.category === 'amk' ? 'yellow' : 'cyan';
</script>

<template>
    <Head title="Daftar - Tiket Bola Percuma" />

    <div class="min-h-screen bg-[#0B1A2E]">
        <div class="relative overflow-hidden">
            <!-- Background glow -->
            <div class="absolute -top-40 -left-40 h-80 w-80 rounded-full bg-yellow-400/10 blur-3xl"></div>
            <div class="absolute -top-20 -right-20 h-60 w-60 rounded-full bg-green-500/10 blur-3xl"></div>

            <div class="mx-auto max-w-lg px-4 pt-8 pb-12 sm:px-6">
                <!-- Back link -->
                <Link href="/" class="mb-6 inline-flex items-center gap-1.5 text-sm text-gray-400 transition hover:text-yellow-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </Link>

                <!-- Header -->
                <div class="mb-6 text-center">
                    <h1 class="text-2xl font-extrabold text-white sm:text-3xl">Daftar Sekarang</h1>
                    <div class="mt-3 inline-block rounded-xl border border-white/10 bg-white/5 px-4 py-2 backdrop-blur-sm">
                        <p class="text-xs leading-relaxed text-gray-400">{{ categoryLabel }}</p>
                    </div>
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
                                :class="category === 'amk'
                                    ? 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 shadow-yellow-500/25 hover:from-yellow-300 hover:to-yellow-400 hover:shadow-yellow-500/40 focus:ring-yellow-400'
                                    : 'bg-gradient-to-r from-[#00B4D8] to-[#0096c7] text-white shadow-[#00B4D8]/25 hover:from-[#48cae4] hover:to-[#00B4D8] hover:shadow-[#00B4D8]/40 focus:ring-[#00B4D8]'"
                                class="w-full rounded-xl px-6 py-3.5 text-sm font-extrabold shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#0B1A2E] disabled:opacity-50"
                            >
                                <span v-if="form.processing" class="flex items-center justify-center gap-2">
                                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                    </svg>
                                    Menghantar...
                                </span>
                                <span v-else>Hantar Pendaftaran</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
