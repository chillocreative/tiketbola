<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    category: String,
    categoryLabel: String,
});

const form = useForm({
    name: '',
    ic_number: '',
    phone: '',
    address: '',
    category: props.category,
});

const agreed = ref(false);

const submitted = ref(false);
const showForm = computed(() => !submitted.value && !props.flash?.success);

const onlyDigits = (field, max) => {
    form[field] = form[field].replace(/\D/g, '').slice(0, max);
};

const submit = () => {
    form.transform((data) => ({
        ...data,
        name: data.name.toUpperCase(),
        address: data.address.toUpperCase(),
    })).post(route('submissions.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('name', 'ic_number', 'phone', 'address');
            submitted.value = true;
        },
    });
};
</script>

<template>
    <Head title="Daftar - Tiket Bola Percuma" />

    <div class="min-h-screen bg-[#0B1A2E]">
        <div class="relative overflow-hidden">
            <div class="absolute -top-40 -left-40 h-80 w-80 rounded-full bg-yellow-400/10 blur-3xl"></div>
            <div class="absolute -top-20 -right-20 h-60 w-60 rounded-full bg-green-500/10 blur-3xl"></div>

            <div class="mx-auto max-w-lg px-4 pt-8 pb-12 sm:px-6">
                <!-- Back link -->
                <a :href="route('submissions.create')" class="mb-6 inline-flex items-center gap-1.5 text-sm text-gray-400 transition hover:text-yellow-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>

                <!-- Header -->
                <div class="mb-6 text-center">
                    <h1 class="text-2xl font-extrabold text-white sm:text-3xl">Daftar Sekarang</h1>
                    <div class="mt-3 inline-block rounded-xl border border-white/10 bg-white/5 px-4 py-2 backdrop-blur-sm">
                        <p class="text-xs leading-relaxed text-gray-400">{{ categoryLabel }}</p>
                    </div>
                </div>

                <!-- Success State (replaces form) -->
                <div v-if="submitted || $page.props.flash?.success" class="text-center">
                    <div class="mx-auto mb-6 overflow-hidden rounded-2xl border border-green-500/20 bg-green-500/5 p-8 backdrop-blur-sm">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-500">
                            <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold text-white">Pendaftaran Berjaya!</h3>
                        <p class="mb-1 text-sm text-gray-300">Terima kasih kerana mendaftar.</p>
                        <p class="text-sm text-gray-400">Anda akan menerima notifikasi WhatsApp mengenai status permohonan anda.</p>
                    </div>
                    <a
                        :href="route('submissions.create')"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-yellow-400 to-yellow-500 px-6 py-3 text-sm font-extrabold text-gray-900 shadow-lg shadow-yellow-500/25 transition hover:from-yellow-300 hover:to-yellow-400"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />
                        </svg>
                        Kembali ke Halaman Utama
                    </a>
                </div>

                <!-- Form Card -->
                <div v-else class="overflow-hidden rounded-2xl border border-white/10 bg-white/5 shadow-2xl backdrop-blur-sm">
                    <div class="p-5 sm:p-7">
                        <form @submit.prevent="submit" class="space-y-5">
                            <div>
                                <label for="name" class="mb-1.5 block text-sm font-semibold text-gray-200">
                                    Nama <span class="text-yellow-400">*</span>
                                </label>
                                <input id="name" v-model="form.name" type="text" required
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm uppercase text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                    placeholder="Masukkan nama penuh" />
                                <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-400">{{ form.errors.name }}</p>
                            </div>

                            <div>
                                <label for="ic_number" class="mb-1.5 block text-sm font-semibold text-gray-200">
                                    No Kad Pengenalan <span class="text-yellow-400">*</span>
                                </label>
                                <input id="ic_number" v-model="form.ic_number" type="text" inputmode="numeric" required maxlength="12"
                                    @input="onlyDigits('ic_number', 12)"
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                    placeholder="cth: 901234105678" />
                                <p v-if="form.errors.ic_number" class="mt-1.5 text-xs text-red-400">{{ form.errors.ic_number }}</p>
                            </div>

                            <div>
                                <label for="phone" class="mb-1.5 block text-sm font-semibold text-gray-200">
                                    No Telefon <span class="text-yellow-400">*</span>
                                </label>
                                <input id="phone" v-model="form.phone" type="tel" inputmode="numeric" required maxlength="11"
                                    @input="onlyDigits('phone', 11)"
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                    placeholder="cth: 01234567890" />
                                <p v-if="form.errors.phone" class="mt-1.5 text-xs text-red-400">{{ form.errors.phone }}</p>
                            </div>

                            <div>
                                <label for="address" class="mb-1.5 block text-sm font-semibold text-gray-200">
                                    Alamat Rumah <span class="text-yellow-400">*</span>
                                </label>
                                <textarea id="address" v-model="form.address" rows="3" required
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm uppercase text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                    placeholder="Masukkan alamat rumah penuh"></textarea>
                                <p v-if="form.errors.address" class="mt-1.5 text-xs text-red-400">{{ form.errors.address }}</p>
                            </div>

                            <!-- Disclaimer -->
                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="agree" v-model="agreed"
                                    class="mt-0.5 h-4 w-4 shrink-0 rounded border-white/20 bg-white/10 text-yellow-400 focus:ring-yellow-400 focus:ring-offset-0" />
                                <label for="agree" class="cursor-pointer text-xs leading-relaxed text-gray-400">
                                    Saya dengan ini bersetuju dengan syarat-syarat yang telah ditetapkan seperti di atas. Saya juga faham bahawa pihak urusetia mempunyai hak penuh untuk memberi atau menolak permohonan tiket bola percuma ini tanpa sebarang alasan.
                                </label>
                            </div>

                            <button type="submit" :disabled="form.processing || !agreed"
                                :class="!agreed && !form.processing
                                    ? 'bg-white/10 text-gray-500 cursor-not-allowed'
                                    : category === 'amk'
                                        ? 'bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 shadow-yellow-500/25 hover:from-yellow-300 hover:to-yellow-400 hover:shadow-yellow-500/40 focus:ring-yellow-400'
                                        : 'bg-gradient-to-r from-[#00B4D8] to-[#0096c7] text-white shadow-[#00B4D8]/25 hover:from-[#48cae4] hover:to-[#00B4D8] hover:shadow-[#00B4D8]/40 focus:ring-[#00B4D8]'"
                                class="w-full rounded-xl px-6 py-3.5 text-sm font-extrabold shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#0B1A2E] disabled:opacity-100">
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
