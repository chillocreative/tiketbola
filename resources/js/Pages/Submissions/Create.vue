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
            setTimeout(() => submitted.value = false, 5000);
        },
    });
};
</script>

<template>
    <Head title="Submit Form" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900">TiketBola</h1>
                <p class="mt-2 text-lg text-gray-600">Submit your request below</p>
            </div>

            <!-- Success Message -->
            <div
                v-if="submitted || $page.props.flash?.success"
                class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4"
            >
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-2 text-sm font-medium text-green-700">Submission sent successfully!</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="rounded-xl bg-white p-8 shadow-lg">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Enter your full name"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input
                            id="phone"
                            v-model="form.phone"
                            type="tel"
                            required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. 0123456789"
                        />
                        <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="your@email.com"
                        />
                        <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea
                            id="message"
                            v-model="form.message"
                            rows="4"
                            required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Your message..."
                        ></textarea>
                        <p v-if="form.errors.message" class="mt-1 text-sm text-red-600">{{ form.errors.message }}</p>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                    >
                        <span v-if="form.processing">Submitting...</span>
                        <span v-else>Submit</span>
                    </button>
                </form>
            </div>

            <!-- Admin link -->
            <div class="mt-6 text-center">
                <a :href="route('login')" class="text-sm text-gray-500 hover:text-indigo-600">
                    Admin Login
                </a>
            </div>
        </div>
    </div>
</template>
