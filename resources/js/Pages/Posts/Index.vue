<script setup>
import { Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
defineProps(['posts']);

</script>

<template>
    <Head title="Posts" />
    <AuthenticatedLayout>

    <template #header>
        <h1 class="text-xl font-semibold">Posts</h1>
    </template>

    <div class="max-w-5xl mx-auto pb-16">
        <Link href="/post/create">
            <h2 class="btn-primary inline-block mt-4 mb-5 float-right">Create Post</h2>
        </Link>

        <table class="border w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="tbl-th">Id</th>
                    <th class="tbl-th">Post Title</th>
                    <th class="tbl-th">Created By</th>
                    <th class="tbl-th">Description</th>
                    <th class="tbl-th">Image</th>
                    <th class="tbl-th">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="post in posts.data" :key="post.id">
                    <td class="p-2 border"> {{ post.id }}</td>
                    <td class="p-2 border"> {{ post.title }}</td>
                    <td class="p-2 border"> {{ post.user.name }}</td>
                    <td class="p-2 border">{{ post.body }}</td>
                    <td class="p-2 border">
                        <img :src="`/storage/${post.image}`"  class="w-20 h-20 object-cover">
                    </td>
                    <td class="p-2 border">
                        <Link :href="`/post/edit/${post.id}`"  class="text-blue-600 hover:underline pr-3">Edit</Link>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4 flex gap-2">
             <template v-for="(link, index) in posts.links" :key="index">

                    <!-- If link is clickable -->
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        v-html="link.label"
                        class="px-3 py-1 border rounded"
                        :class="{ 'bg-gray-300': link.active }"
                    />

                    <!-- If link is disabled -->
                    <span
                        v-else
                        v-html="link.label"
                        class="px-3 py-1 border rounded text-gray-400 cursor-not-allowed"
                    />

                </template>
        </div>
    </div>
    </AuthenticatedLayout>

</template>
