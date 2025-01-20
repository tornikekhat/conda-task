<template>
    <div class="max-w-lg mx-auto mt-10 p-4 border rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold text-center mb-6">Contact Us</h2>
        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                    v-model="name"
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
                    v-model="email"
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
                    v-model="subject"
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
                    v-model="message"
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
import {ref} from 'vue';
import axios from 'axios';
import {useForm, useField} from 'vee-validate';
import {object, string} from 'yup';

const schema = object({
    name: string().required('Name is required.'),
    email: string().required('Email is required.').email('Invalid email address.'),
    subject: string().required('Subject is required.'),
    message: string().required('Message is required.'),
});

const {handleSubmit, errors, isSubmitting, resetForm} = useForm({
    validationSchema: schema,
});

const {value: name} = useField('name');
const {value: email} = useField('email');
const {value: subject} = useField('subject');
const {value: message} = useField('message');

const submissionStatus = ref(null);
const submissionStatusClass = ref('');

const submitForm = handleSubmit(async (values) => {
    submissionStatus.value = null;
    submissionStatusClass.value = '';

    try {
        const response = await axios.post('/api/contacts', values);
        submissionStatus.value = response.data.message;
        submissionStatusClass.value = 'text-green-500';

        resetForm();
    } catch (error) {
        submissionStatus.value = 'Failed to send message.';
        submissionStatusClass.value = 'text-red-500';
    } finally {
        setTimeout(() => {
            submissionStatus.value = null;
        }, 3000);
    }
});
</script>
