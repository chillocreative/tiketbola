<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    settings: Object,
});

const form = useForm({
    api_url: props.settings?.api_url || 'https://sendora.id/api/v1',
    api_token: '',
    sender_number: props.settings?.sender_number || '',
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
            message: error.response?.data?.message || 'Connection test failed.',
        };
    } finally {
        testing.value = false;
    }
};
</script>

<template>
    <Head title="Sendora Settings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Sendora WhatsApp Settings
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ $page.props.flash.success }}
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="save" class="space-y-6">
                            <!-- API URL -->
                            <div>
                                <label for="api_url" class="block text-sm font-medium text-gray-700">API URL</label>
                                <input
                                    id="api_url"
                                    v-model="form.api_url"
                                    type="url"
                                    required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <p v-if="form.errors.api_url" class="mt-1 text-sm text-red-600">{{ form.errors.api_url }}</p>
                            </div>

                            <!-- API Token -->
                            <div>
                                <label for="api_token" class="block text-sm font-medium text-gray-700">
                                    API Token
                                    <span v-if="settings?.has_token" class="ml-1 text-xs text-green-600">(token saved, leave empty to keep current)</span>
                                </label>
                                <input
                                    id="api_token"
                                    v-model="form.api_token"
                                    type="password"
                                    :placeholder="settings?.has_token ? '********' : 'Enter API token'"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <p v-if="form.errors.api_token" class="mt-1 text-sm text-red-600">{{ form.errors.api_token }}</p>
                            </div>

                            <!-- Sender Number -->
                            <div>
                                <label for="sender_number" class="block text-sm font-medium text-gray-700">Sender Number</label>
                                <input
                                    id="sender_number"
                                    v-model="form.sender_number"
                                    type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="e.g. 60123456789"
                                />
                                <p v-if="form.errors.sender_number" class="mt-1 text-sm text-red-600">{{ form.errors.sender_number }}</p>
                            </div>

                            <!-- Timeout -->
                            <div>
                                <label for="timeout" class="block text-sm font-medium text-gray-700">Timeout (seconds)</label>
                                <input
                                    id="timeout"
                                    v-model.number="form.timeout"
                                    type="number"
                                    min="5"
                                    max="120"
                                    class="mt-1 block w-32 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <p v-if="form.errors.timeout" class="mt-1 text-sm text-red-600">{{ form.errors.timeout }}</p>
                            </div>

                            <!-- Active Toggle -->
                            <div class="flex items-center space-x-3">
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="checkbox" v-model="form.is_active" class="peer sr-only" />
                                    <div class="peer h-6 w-11 rounded-full bg-gray-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-indigo-600 peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                                </label>
                                <span class="text-sm font-medium text-gray-700">Active</span>
                            </div>

                            <!-- Buttons -->
                            <div class="flex items-center space-x-4">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 disabled:opacity-50"
                                >
                                    <span v-if="form.processing">Saving...</span>
                                    <span v-else>Save Settings</span>
                                </button>

                                <button
                                    type="button"
                                    @click="testConnection"
                                    :disabled="testing"
                                    class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 disabled:opacity-50"
                                >
                                    <span v-if="testing">Testing...</span>
                                    <span v-else>Test Connection</span>
                                </button>
                            </div>
                        </form>

                        <!-- Test Result -->
                        <div v-if="testResult" class="mt-6">
                            <div
                                :class="testResult.success ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700'"
                                class="rounded-lg border p-4 text-sm"
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
