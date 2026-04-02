<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const tableScroller = ref(null);
let isDown = false;
let startX = 0;
let scrollLeft = 0;

const onMouseDown = (e) => {
    isDown = true;
    tableScroller.value.style.cursor = 'grabbing';
    startX = e.pageX - tableScroller.value.offsetLeft;
    scrollLeft = tableScroller.value.scrollLeft;
};
const onMouseLeave = () => { isDown = false; tableScroller.value.style.cursor = 'grab'; };
const onMouseUp = () => { isDown = false; tableScroller.value.style.cursor = 'grab'; };
const onMouseMove = (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - tableScroller.value.offsetLeft;
    tableScroller.value.scrollLeft = scrollLeft - (x - startX);
};

const props = defineProps({
    submissions: Object,
});

const processing = ref(null);
const selected = ref([]);

const allSelected = computed({
    get: () => props.submissions.data.length > 0 && selected.value.length === props.submissions.data.length,
    set: (val) => { selected.value = val ? props.submissions.data.map(s => s.id) : []; },
});

const approve = (id) => {
    if (!confirm('Luluskan permohonan ini? Notifikasi WhatsApp akan dihantar.')) return;
    processing.value = id;
    router.post(route('admin.submissions.verify', id), {}, { preserveScroll: true, onFinish: () => processing.value = null });
};

const reject = (id) => {
    if (!confirm('Tolak permohonan ini? Notifikasi WhatsApp akan dihantar.')) return;
    processing.value = id;
    router.post(route('admin.submissions.reject', id), {}, { preserveScroll: true, onFinish: () => processing.value = null });
};

const destroy = (id) => {
    if (!confirm('Padam pendaftaran ini?')) return;
    router.delete(route('admin.submissions.destroy', id), { preserveScroll: true });
};

const bulkDelete = () => {
    if (selected.value.length === 0) return;
    if (!confirm(`Padam ${selected.value.length} pendaftaran yang dipilih?`)) return;
    router.post(route('admin.submissions.bulkDelete'), { ids: selected.value }, {
        preserveScroll: true,
        onSuccess: () => selected.value = [],
    });
};

const copiedId = ref(null);
const copyIc = (ic, id) => {
    navigator.clipboard.writeText(ic);
    copiedId.value = id;
    setTimeout(() => { copiedId.value = null; }, 1500);
};

const statusLabel = (s) => ({ verified: 'Diluluskan', rejected: 'Ditolak', pending: 'Menunggu' }[s] || s);
const statusClass = (s) => ({
    verified: 'bg-green-500/10 text-green-400 ring-1 ring-green-500/20',
    rejected: 'bg-red-500/10 text-red-400 ring-1 ring-red-500/20',
    pending: 'bg-yellow-400/10 text-yellow-400 ring-1 ring-yellow-400/20',
}[s] || '');
const categoryLabel = (c) => c === 'mbsp' ? 'MBSP' : 'AMK';
const categoryClass = (c) => c === 'mbsp'
    ? 'bg-[#00B4D8]/10 text-[#00B4D8] ring-1 ring-[#00B4D8]/20'
    : 'bg-yellow-400/10 text-yellow-400 ring-1 ring-yellow-400/20';
</script>

<template>
    <Head title="Senarai Pendaftaran" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white">Senarai Pendaftaran</h2>
                <div class="flex items-center gap-2">
                    <a :href="route('admin.submissions.export')"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-gray-300 transition hover:bg-white/10 hover:text-white sm:text-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Excel
                    </a>
                    <Link :href="route('admin.submissions.create')"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-gradient-to-r from-yellow-400 to-yellow-500 px-3 py-2 text-xs font-bold text-gray-900 shadow-lg shadow-yellow-500/20 transition hover:shadow-yellow-500/30 sm:text-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </Link>
                </div>
            </div>
        </template>

        <div class="p-4 sm:p-6 lg:p-8">
            <div v-if="$page.props.flash?.success" class="mb-4 rounded-xl border border-green-500/20 bg-green-500/10 px-4 py-3 text-sm text-green-400">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.warning" class="mb-4 rounded-xl border border-yellow-400/20 bg-yellow-400/10 px-4 py-3 text-sm text-yellow-400">
                {{ $page.props.flash.warning }}
            </div>

            <!-- Bulk Actions Bar -->
            <div v-if="selected.length > 0" class="mb-3 flex items-center gap-3 rounded-lg border border-yellow-400/20 bg-yellow-400/5 px-4 py-2.5">
                <span class="text-sm font-semibold text-yellow-400">{{ selected.length }} dipilih</span>
                <button @click="bulkDelete"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-red-500 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-red-400">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Padam Dipilih
                </button>
                <button @click="selected = []" class="text-xs text-gray-400 transition hover:text-white">Nyahpilih</button>
            </div>

            <div class="overflow-hidden border border-white/5 bg-white/5 backdrop-blur-sm">
                <div v-if="submissions.data.length === 0" class="py-16 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="mt-3 text-sm text-gray-500">Tiada pendaftaran lagi.</p>
                </div>

                <div v-else ref="tableScroller" class="overflow-x-auto cursor-grab select-none" @mousedown="onMouseDown" @mouseleave="onMouseLeave" @mouseup="onMouseUp" @mousemove="onMouseMove">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-white/5">
                                <th class="px-4 py-3.5">
                                    <input type="checkbox" v-model="allSelected"
                                        class="h-4 w-4 rounded border-white/20 bg-white/10 text-yellow-400 focus:ring-yellow-400 focus:ring-offset-0" />
                                </th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">#</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Nama</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">No KP</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Telefon</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Alamat</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Kategori</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tarikh</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr v-for="(s, i) in submissions.data" :key="s.id" class="transition hover:bg-white/[0.02]"
                                :class="selected.includes(s.id) ? 'bg-yellow-400/[0.03]' : ''">
                                <td class="px-4 py-4">
                                    <input type="checkbox" :value="s.id" v-model="selected"
                                        class="h-4 w-4 rounded border-white/20 bg-white/10 text-yellow-400 focus:ring-yellow-400 focus:ring-offset-0" />
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">{{ (submissions.current_page - 1) * submissions.per_page + i + 1 }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm font-semibold text-white">{{ s.name }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-400 cursor-pointer hover:text-yellow-400 transition" @click.stop="copyIc(s.ic_number, s.id)" :title="copiedId === s.id ? 'Disalin!' : 'Klik untuk salin'">
                                    {{ s.ic_number }}
                                    <span v-if="copiedId === s.id" class="ml-1 text-xs text-green-400">Disalin!</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-400">{{ s.phone }}</td>
                                <td class="max-w-[200px] truncate px-4 py-4 text-sm text-gray-400">{{ s.address }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm">
                                    <span :class="categoryClass(s.category)" class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold">{{ categoryLabel(s.category) }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm">
                                    <span :class="statusClass(s.status)" class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold">{{ statusLabel(s.status) }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-500">{{ new Date(s.created_at).toLocaleDateString('ms-MY') }}</td>
                                <td class="whitespace-nowrap px-4 py-4 text-sm">
                                    <div class="flex items-center gap-1.5">
                                        <template v-if="s.status === 'pending'">
                                            <button @click="approve(s.id)" :disabled="processing === s.id"
                                                class="rounded-lg bg-green-500 px-2.5 py-1 text-[10px] font-bold text-white transition hover:bg-green-400 disabled:opacity-50">Lulus</button>
                                            <button @click="reject(s.id)" :disabled="processing === s.id"
                                                class="rounded-lg bg-red-500 px-2.5 py-1 text-[10px] font-bold text-white transition hover:bg-red-400 disabled:opacity-50">Tolak</button>
                                        </template>
                                        <span v-else-if="s.status === 'verified'" class="text-[10px] font-medium text-green-400">Diluluskan</span>
                                        <span v-else-if="s.status === 'rejected'" class="text-[10px] font-medium text-red-400">Ditolak</span>
                                        <Link :href="route('admin.submissions.edit', s.id)"
                                            class="rounded-lg bg-white/10 px-2.5 py-1 text-[10px] font-bold text-gray-300 transition hover:bg-white/20 hover:text-white">Edit</Link>
                                        <button @click="destroy(s.id)"
                                            class="rounded-lg bg-white/5 px-2.5 py-1 text-[10px] font-bold text-gray-500 transition hover:bg-red-500/20 hover:text-red-400">Padam</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="submissions.last_page > 1" class="flex justify-center border-t border-white/5 px-4 py-4">
                    <div class="flex space-x-1">
                        <template v-for="link in submissions.links" :key="link.label">
                            <button v-if="link.url" @click="router.get(link.url)"
                                :class="link.active ? 'bg-yellow-400 text-gray-900' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white'"
                                class="rounded-lg px-3 py-1.5 text-xs font-semibold transition" v-html="link.label" />
                            <span v-else class="rounded-lg bg-white/[0.02] px-3 py-1.5 text-xs text-gray-600" v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
