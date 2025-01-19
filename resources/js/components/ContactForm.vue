<template>
    <div class="max-w-lg mx-auto mt-10 p-4 border rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-center mb-6">Contact Us</h2>
        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                    v-model="form.name"
                    id="name"
                    type="text"
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                    :class="{'border-red-500': errors.name}"
                />
                <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    v-model="form.email"
                    id="email"
                    type="email"
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                    :class="{'border-red-500': errors.email}"
                />
                <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
            </div>

            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                <input
                    v-model="form.subject"
                    id="subject"
                    type="text"
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                    :class="{'border-red-500': errors.subject}"
                />
                <p v-if="errors.subject" class="text-red-500 text-sm mt-1">{{ errors.subject }}</p>
            </div>

            <div class="mb-4">
                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                <textarea
                    v-model="form.message"
                    id="message"
                    rows="4"
                    class="mt-1 p-2 w-full border border-gray-300 rounded-md"
                    :class="{'border-red-500': errors.message}"
                ></textarea>
                <p v-if="errors.message" class="text-red-500 text-sm mt-1">{{ errors.message }}</p>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600"
                :disabled="isSubmitting"
            >
                Send Message
            </button>
        </form>

        <p v-if="submissionStatus" :class="submissionStatusClass" class="mt-4 text-center">
            {{ submissionStatus }}
        </p>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const form = ref({
    name: '',
    email: '',
    subject: '',
    message: '',
});
const errors = ref({});
const isSubmitting = ref(false);
const submissionStatus = ref(null);
const submissionStatusClass = ref('');

const submitForm = async () => {
    errors.value = {};
    submissionStatus.value = null;
    submissionStatusClass.value = '';

    if (!form.value.name || !form.value.email || !form.value.subject || !form.value.message) {
        errors.value = {
            name: form.value.name ? null : 'Name is required.',
            email: form.value.email ? null : 'Email is required.',
            subject: form.value.subject ? null : 'Subject is required.',
            message: form.value.message ? null : 'Message is required.',
        };
        return;
    }

    if (!isValidEmail(form.value.email)) {
        errors.value.email = 'Invalid email address.';
        return;
    }

    isSubmitting.value = true;

    try {
        const response = await axios.post('/api/contacts', form.value);
        submissionStatus.value = response.data.message;
        submissionStatusClass.value = 'text-green-500';
        form.value = {name: '', email: '', subject: '', message: ''};
    } catch (error) {
        submissionStatus.value = 'Failed to send message.';
        submissionStatusClass.value = 'text-red-500';
    } finally {
        isSubmitting.value = false;
    }
};

const isValidEmail = (email) => {
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return regex.test(email);
};
</script>
