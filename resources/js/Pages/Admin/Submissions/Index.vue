<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    submissions: Object,
});

const verifying = ref(null);

const verify = (id) => {
    if (!confirm('Verify this submission and send WhatsApp notification?')) return;
    verifying.value = id;
    router.post(route('admin.submissions.verify', id), {}, {
        preserveScroll: true,
        onFinish: () => verifying.value = null,
    });
};

const statusClass = (status) => {
    return status === 'verified'
        ? 'bg-green-100 text-green-800'
        : 'bg-yellow-100 text-yellow-800';
};
</script>

<template>
    <Head title="Submissions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Submissions
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                <div v-if="$page.props.flash?.success" class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash?.warning" class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-700">
                    {{ $page.props.flash.warning }}
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="submissions.data.length === 0" class="py-8 text-center text-gray-500">
                            No submissions yet.
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">#</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Phone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Message</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="(submission, index) in submissions.data" :key="submission.id">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ (submissions.current_page - 1) * submissions.per_page + index + 1 }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ submission.name }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ submission.phone }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ submission.email }}
                                        </td>
                                        <td class="max-w-xs truncate px-6 py-4 text-sm text-gray-500">
                                            {{ submission.message }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            <span
                                                :class="statusClass(submission.status)"
                                                class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                            >
                                                {{ submission.status }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                            {{ new Date(submission.created_at).toLocaleDateString() }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            <button
                                                v-if="submission.status === 'pending'"
                                                @click="verify(submission.id)"
                                                :disabled="verifying === submission.id"
                                                class="rounded bg-green-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-green-700 disabled:opacity-50"
                                            >
                                                <span v-if="verifying === submission.id">Verifying...</span>
                                                <span v-else>Verify</span>
                                            </button>
                                            <span v-else class="text-xs text-gray-400">Done</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="submissions.last_page > 1" class="mt-6 flex justify-center space-x-1">
                            <template v-for="link in submissions.links" :key="link.label">
                                <button
                                    v-if="link.url"
                                    @click="router.get(link.url)"
                                    :class="link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                                    class="rounded border px-3 py-1 text-sm"
                                    v-html="link.label"
                                />
                                <span
                                    v-else
                                    class="rounded border bg-gray-100 px-3 py-1 text-sm text-gray-400"
                                    v-html="link.label"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
