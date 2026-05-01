<script setup>
import { Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
defineProps({
    users: Array
});

</script>

<template>
    <Head title="Users" />
    <AuthenticatedLayout>

    <template #header>
        <h1 class="text-xl font-semibold">Users</h1>
    </template>

    <div class="max-w-5xl mx-auto">
        <Link href="/users/create">
            <h2 class="btn-primary inline-block mt-4 mb-5 float-right">Create User</h2>
        </Link>

        <table class="border w-full">
            <thead class="bg-gray-100">
                <tr>
                     <th class="tbl-th">Id</th>
                    <th class="tbl-th">Name</th>
                    <th class="tbl-th">Email</th>
                    <th class="tbl-th">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td class="p-2 border"> {{ user.id }}</td>
                    <td class="p-2 border"> {{ user.name }}</td>
                    <td class="p-2 border">{{ user.email }}</td>

                    <td class="p-2 border">
                        <Link :href="`/users/${user.id}/edit`"  class="text-blue-600 hover:underline pr-3">Edit</Link>

                        <Link
                            :href="`/users/${user.id}`"
                            method="delete"
                            as="button"  class="text-red-600 hover:underline"
                        >
                            Delete
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </AuthenticatedLayout>

</template>
