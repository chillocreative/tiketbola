<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Lupa Kata Laluan" />

        <!-- Header -->
        <div class="mb-6 text-center">
            <h2 class="text-xl font-bold text-white">Lupa Kata Laluan</h2>
            <p class="mt-2 text-sm leading-relaxed text-gray-400">
                Tiada masalah. Masukkan emel anda dan kami akan hantar pautan untuk tetapkan semula kata laluan.
            </p>
        </div>

        <!-- Status -->
        <div
            v-if="status"
            class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm font-medium text-green-400"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <!-- Email -->
            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-200">
                    Emel
                </label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                    placeholder="contoh@email.com"
                />
                <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400">{{ form.errors.email }}</p>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col gap-3">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-xl bg-gradient-to-r from-yellow-400 to-yellow-500 px-6 py-3 text-sm font-extrabold text-gray-900 shadow-lg shadow-yellow-500/25 transition-all hover:from-yellow-300 hover:to-yellow-400 hover:shadow-yellow-500/40 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 focus:ring-offset-[#0B1A2E] disabled:opacity-50"
                >
                    <span v-if="form.processing" class="flex items-center justify-center gap-2">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                        Menghantar...
                    </span>
                    <span v-else>Hantar Pautan Reset</span>
                </button>

                <Link
                    :href="route('login')"
                    class="w-full rounded-xl border border-white/10 bg-white/5 px-6 py-3 text-center text-sm font-semibold text-gray-300 transition hover:bg-white/10 hover:text-white"
                >
                    Kembali ke Log Masuk
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
