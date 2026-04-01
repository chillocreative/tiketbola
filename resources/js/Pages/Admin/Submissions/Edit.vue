<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    submission: Object,
});

const form = useForm({
    name: props.submission.name,
    ic_number: props.submission.ic_number,
    phone: props.submission.phone,
    address: props.submission.address,
    category: props.submission.category,
    status: props.submission.status,
});

const onlyDigits = (field, max) => {
    form[field] = form[field].replace(/\D/g, '').slice(0, max);
};

const submit = () => {
    form.transform((data) => ({
        ...data,
        name: data.name.toUpperCase(),
        address: data.address.toUpperCase(),
    })).put(route('admin.submissions.update', props.submission.id));
};
</script>

<template>
    <Head title="Edit Pendaftaran" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('admin.submissions')" class="text-gray-400 transition hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </Link>
                <h2 class="text-xl font-bold text-white">Edit Pendaftaran</h2>
            </div>
        </template>

        <div class="p-4 sm:p-6 lg:p-8">
            <div class="mx-auto max-w-2xl">
                <div class="overflow-hidden rounded-2xl border border-white/5 bg-white/5 backdrop-blur-sm">
                    <div class="p-5 sm:p-7">
                        <form @submit.prevent="submit" class="space-y-5">
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-gray-200">Nama</label>
                                <input v-model="form.name" type="text" required
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm uppercase text-white placeholder-gray-500 ring-1 ring-white/10 focus:ring-2 focus:ring-yellow-400" />
                                <p v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-gray-200">No Kad Pengenalan</label>
                                <input v-model="form.ic_number" type="text" inputmode="numeric" required maxlength="12" @input="onlyDigits('ic_number', 12)"
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 focus:ring-2 focus:ring-yellow-400" />
                                <p v-if="form.errors.ic_number" class="mt-1 text-xs text-red-400">{{ form.errors.ic_number }}</p>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-gray-200">No Telefon</label>
                                <input v-model="form.phone" type="tel" inputmode="numeric" required maxlength="11" @input="onlyDigits('phone', 11)"
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 focus:ring-2 focus:ring-yellow-400" />
                                <p v-if="form.errors.phone" class="mt-1 text-xs text-red-400">{{ form.errors.phone }}</p>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-semibold text-gray-200">Alamat Rumah</label>
                                <textarea v-model="form.address" rows="3" required
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm uppercase text-white placeholder-gray-500 ring-1 ring-white/10 focus:ring-2 focus:ring-yellow-400"></textarea>
                                <p v-if="form.errors.address" class="mt-1 text-xs text-red-400">{{ form.errors.address }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1.5 block text-sm font-semibold text-gray-200">Kategori</label>
                                    <select v-model="form.category"
                                        class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white ring-1 ring-white/10 focus:ring-2 focus:ring-yellow-400">
                                        <option value="amk" class="bg-[#0B1A2E]">AMK</option>
                                        <option value="mbsp" class="bg-[#0B1A2E]">MBSP</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-semibold text-gray-200">Status</label>
                                    <select v-model="form.status"
                                        class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white ring-1 ring-white/10 focus:ring-2 focus:ring-yellow-400">
                                        <option value="pending" class="bg-[#0B1A2E]">Menunggu</option>
                                        <option value="verified" class="bg-[#0B1A2E]">Diluluskan</option>
                                        <option value="rejected" class="bg-[#0B1A2E]">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="submit" :disabled="form.processing"
                                    class="rounded-xl bg-gradient-to-r from-yellow-400 to-yellow-500 px-6 py-2.5 text-sm font-bold text-gray-900 shadow-lg shadow-yellow-500/20 transition disabled:opacity-50">
                                    {{ form.processing ? 'Menyimpan...' : 'Kemaskini' }}
                                </button>
                                <Link :href="route('admin.submissions')" class="rounded-xl border border-white/10 bg-white/5 px-6 py-2.5 text-sm font-semibold text-gray-300 transition hover:bg-white/10">Batal</Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
