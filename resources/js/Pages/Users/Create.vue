<script setup>
import { Link, Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { reactive } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: ''
});

const errors = reactive({
    name: '',
    email: '',
    password: ''
});

const validate = () => {
    errors.name = '';
    errors.email = '';
    errors.password = '';

    if (!form.name) {
        errors.name = 'Name is required';
    }

    if (!form.email) {
        errors.email = 'Email is required';
    } else if (!form.email.includes('@')) {
        errors.email = 'Invalid email';
    }

     if (!form.password) {
        errors.password = 'Password is required';
    }

    return !errors.name && !errors.email && !errors.password;
};

const submit = () => {
    if (validate()) {
        form.post('/users');
    }
};
</script>

<template>
    <Head title="Create User" />
    <AuthenticatedLayout>

    <template #header>
        <h1 class="text-xl font-semibold">Create User</h1>
    </template>

    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <Link href="/users" class="text-blue-600 mb-4 inline-block">
                ← Back
            </Link>

            <form @submit.prevent="submit" class="space-y-6">
                <label class="form-lable">Name</label>
                <input type="text" class="form-input" v-model="form.name">

                <div class="form-error" v-if="errors.name">
                    {{ errors.name }}
                </div>

                <div v-else-if="form.errors.name" class="form-error">
                    {{ form.errors.name }}
                </div>

                <label class="form-lable">Email</label>
                <input type="email" v-model="form.email" class="form-input">

                <div class="form-error" v-if="errors.email">
                    {{ errors.email }}
                </div>

                <div v-else-if="form.errors.email" class="form-error">
                    {{ form.errors.email }}
                </div>

                <label class="form-lable">Password</label>
                <input type="password" v-model="form.password" class="form-input">

                <div class="form-error" v-if="errors.password">
                    {{ errors.password }}
                </div>

                <div v-else-if="form.errors.email" class="form-error">
                    {{ form.errors.password }}
                </div>

                <div class="flex justify-end">
                    <button class="btn-primary">Create User</button>
                </div>

            </form>
        </div>
    </div>
    </AuthenticatedLayout>

</template>
