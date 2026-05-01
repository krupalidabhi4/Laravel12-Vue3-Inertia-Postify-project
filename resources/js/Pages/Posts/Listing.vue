<script setup>
import { Link, Head,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue'

const props = defineProps(['posts']);

const loading = ref(false);

const allPosts = ref(props.posts.data) // Here this posts.data is in array return by laravel inertia
const nextPage = ref(props.posts.next_page_url)

const loadMore = () => {
  if (!nextPage.value) return

  loading.value = true;
  router.get(nextPage.value, {}, {
    preserveState: true,
    preserveScroll: true,
    only: ['posts'],
    onSuccess: (page) => {
        // page.props.posts        // object
        // page.props.posts.data   // array
     loading.value = false;
     // here it append old and new data in allpost.value as we use ref to make array reactive so used .value
      allPosts.value = [
        ...allPosts.value,
        ...page.props.posts.data
      ]
      nextPage.value = page.props.posts.next_page_url
    }
  })
}
</script>

<template>
    <Head title="Post Listing" />
    <AuthenticatedLayout>

    <template #header>
        <h1 class="text-xl font-semibold">Posts Listing</h1>
    </template>

    <div class="max-w-5xl mx-auto">
        <div class="max-w-7xl mx-auto p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div v-for="post in allPosts" :key="post.id" class="bg-white rounded-lg shadow overflow-hidden">

                <!-- Image -->
                <img :src="`/storage/${post.image}`" class="w-full h-60 object-cover" />

                <!-- Content -->
                <div class="p-4">
                <h2 class="text-lg font-semibold">{{ post.title }}</h2>

                <p class="text-sm text-gray-500">
                    By {{ post.user.name }} • {{ new Date(post.created_at).toLocaleDateString() }}
                </p>

                <p class="mt-2 text-gray-600 line-clamp-2">
                    {{ post.body }}
                </p>

                <!-- Read More -->
                <a :href="`/post/show/${post.id}`" class="text-blue-600 mt-3 inline-block">
                    Read More →
                </a>
                </div>
            </div>

            </div>

            <!-- Load More Button -->
            <div class="text-center mt-8" v-if="nextPage">
            <button @click="loadMore" class="px-6 py-2 bg-black text-white rounded" :disabled=loading>
                {{ loading ? 'Loading' : 'Load More' }}
            </button>
            </div>

        </div>
    </div>
    </AuthenticatedLayout>

</template>
