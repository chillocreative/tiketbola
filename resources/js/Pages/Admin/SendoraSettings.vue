<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    settings: Object,
});

const form = useForm({
    api_url: props.settings?.api_url || 'https://sendora.cc',
    api_token: '',
    sender_number: props.settings?.sender_number || '',
    device_id: props.settings?.device_id || '',
    is_active: props.settings?.is_active || false,
    timeout: props.settings?.timeout || 30,
});

const testing = ref(false);
const testResult = ref(null);

const save = () => {
    form.post(route('admin.sendora.update'), {
        preserveScroll: true,
    });
};

const testConnection = async () => {
    testing.value = true;
    testResult.value = null;
    try {
        const response = await axios.post(route('admin.sendora.test'));
        testResult.value = response.data;
    } catch (error) {
        testResult.value = {
            success: false,
            message: error.response?.data?.message || 'Ujian sambungan gagal.',
        };
    } finally {
        testing.value = false;
    }
};
</script>

<template>
    <Head title="Tetapan Sendora" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-bold text-white">Tetapan Sendora WhatsApp</h2>
        </template>

        <div class="p-4 sm:p-6 lg:p-8">
            <div class="mx-auto max-w-2xl">
                <!-- Flash Messages -->
                <div v-if="$page.props.flash?.success" class="mb-4 rounded-xl border border-green-500/20 bg-green-500/10 px-4 py-3 text-sm text-green-400">
                    {{ $page.props.flash.success }}
                </div>

                <div class="overflow-hidden rounded-2xl border border-white/5 bg-white/5 backdrop-blur-sm">
                    <div class="p-5 sm:p-7">
                        <form @submit.prevent="save" class="space-y-6">
                            <!-- API URL -->
                            <div>
                                <label for="api_url" class="mb-1.5 block text-sm font-semibold text-gray-200">API URL</label>
                                <input
                                    id="api_url"
                                    v-model="form.api_url"
                                    type="url"
                                    required
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                />
                                <p v-if="form.errors.api_url" class="mt-1.5 text-xs text-red-400">{{ form.errors.api_url }}</p>
                            </div>

                            <!-- API Token -->
                            <div>
                                <label for="api_token" class="mb-1.5 block text-sm font-semibold text-gray-200">
                                    API Token
                                    <span v-if="settings?.has_token" class="ml-1 text-xs text-green-400">(token tersimpan, kosongkan untuk kekalkan)</span>
                                </label>
                                <input
                                    id="api_token"
                                    v-model="form.api_token"
                                    type="password"
                                    :placeholder="settings?.has_token ? '********' : 'Masukkan API token'"
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                />
                                <p v-if="form.errors.api_token" class="mt-1.5 text-xs text-red-400">{{ form.errors.api_token }}</p>
                            </div>

                            <!-- Sender Number -->
                            <div>
                                <label for="sender_number" class="mb-1.5 block text-sm font-semibold text-gray-200">Nombor Penghantar</label>
                                <input
                                    id="sender_number"
                                    v-model="form.sender_number"
                                    type="text"
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                    placeholder="cth: 60123456789"
                                />
                                <p v-if="form.errors.sender_number" class="mt-1.5 text-xs text-red-400">{{ form.errors.sender_number }}</p>
                            </div>

                            <!-- Device ID -->
                            <div>
                                <label for="device_id" class="mb-1.5 block text-sm font-semibold text-gray-200">Device ID</label>
                                <input
                                    id="device_id"
                                    v-model="form.device_id"
                                    type="text"
                                    class="block w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                    placeholder="Masukkan Device ID dari Sendora"
                                />
                                <p v-if="form.errors.device_id" class="mt-1.5 text-xs text-red-400">{{ form.errors.device_id }}</p>
                            </div>

                            <!-- Timeout -->
                            <div>
                                <label for="timeout" class="mb-1.5 block text-sm font-semibold text-gray-200">Timeout (saat)</label>
                                <input
                                    id="timeout"
                                    v-model.number="form.timeout"
                                    type="number"
                                    min="5"
                                    max="120"
                                    class="block w-32 rounded-xl border-0 bg-white/10 px-4 py-3 text-sm text-white placeholder-gray-500 ring-1 ring-white/10 transition focus:bg-white/15 focus:ring-2 focus:ring-yellow-400"
                                />
                                <p v-if="form.errors.timeout" class="mt-1.5 text-xs text-red-400">{{ form.errors.timeout }}</p>
                            </div>

                            <!-- Active Toggle -->
                            <div class="flex items-center gap-3">
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="checkbox" v-model="form.is_active" class="peer sr-only" />
                                    <div class="peer h-6 w-11 rounded-full bg-white/10 ring-1 ring-white/10 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-gray-400 after:transition-all after:content-[''] peer-checked:bg-yellow-400/20 peer-checked:ring-yellow-400/30 peer-checked:after:translate-x-full peer-checked:after:bg-yellow-400"></div>
                                </label>
                                <span class="text-sm font-medium text-gray-300">Aktif</span>
                            </div>

                            <!-- Buttons -->
                            <div class="flex flex-wrap items-center gap-3">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="rounded-xl bg-gradient-to-r from-yellow-400 to-yellow-500 px-6 py-2.5 text-sm font-bold text-gray-900 shadow-lg shadow-yellow-500/20 transition hover:shadow-yellow-500/30 disabled:opacity-50"
                                >
                                    <span v-if="form.processing">Menyimpan...</span>
                                    <span v-else>Simpan Tetapan</span>
                                </button>

                                <button
                                    type="button"
                                    @click="testConnection"
                                    :disabled="testing"
                                    class="rounded-xl border border-white/10 bg-white/5 px-6 py-2.5 text-sm font-semibold text-gray-300 transition hover:bg-white/10 hover:text-white disabled:opacity-50"
                                >
                                    <span v-if="testing">Menguji...</span>
                                    <span v-else>Uji Sambungan</span>
                                </button>
                            </div>
                        </form>

                        <!-- Test Result -->
                        <div v-if="testResult" class="mt-6">
                            <div
                                :class="testResult.success ? 'border-green-500/20 bg-green-500/10 text-green-400' : 'border-red-500/20 bg-red-500/10 text-red-400'"
                                class="rounded-xl border px-4 py-3 text-sm"
                            >
                                {{ testResult.message }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
