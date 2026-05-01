<script setup>
import { Link, Head,router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue'

const props = defineProps(['post', 'liked', 'likesCount', 'userId']);

const isLiked = ref(props.liked)
const likes = ref(props.likesCount)
const authUserId = props.userId
const commentData = ref(props.post.comments)
const comment = ref('')
const editingId = ref(null)
const editText = ref('')

const toggleLike = () => {
  router.post(`/post/like/${props.post.id}`, {}, {
    preserveScroll: true,
    onSuccess: () => {
      isLiked.value = !isLiked.value
      likes.value += isLiked.value ? 1 : -1
    }
  })
}
const submitComment = () => {
  router.post(`/post/comment/${props.post.id}`, {
    comment: comment.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      comment.value = ''
      commentData.value = [
        ...commentData.value,
        ...props.post.comments
      ]
    }
  })
}

const startEdit = (commentItem) => {
  editingId.value = commentItem.id
  editText.value = commentItem.content
}

const cancelEdit = () => {
  editingId.value = null
  editText.value = ''
}

const updateComment = () => {
  router.post(`/post/comment-update/${editingId.value}`, {
    content: editText.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      const commentIndex = commentData.value.findIndex(c => c.id === editingId.value)
      if (commentIndex !== -1) {
        commentData.value[commentIndex].content = editText.value
      }
      editingId.value = null
      editText.value = ''
    }
  })
}

</script>

<template>
    <Head title="Show Post" />
    <AuthenticatedLayout>


    <div class="max-w-5xl mx-auto">
        <div class="max-w-7xl mx-auto p-6">
             <!-- Image -->
            <div class="w-full h-[500px] md:h-[600px] overflow-hidden">
                <img :src="`/storage/${post.image}`" class="w-full h-full object-cover" />
            </div>

            <div class="max-w-4xl mx-auto p-6">

                <h1 class="text-2xl font-bold">{{ post.title }}</h1>

                <p class="text-gray-500">
                    By {{ post.author }} • {{ new Date(post.created_at).toDateString() }}
                </p>

                 <p class="mt-4 text-gray-700">
                    {{ post.body }}
                </p>

                <!-- Like and Comment Icons -->
                <div class="flex items-center gap-6 my-9">
                  <!-- Like -->
                  <button @click="toggleLike" class="flex items-center gap-2">
                    <span :class="isLiked ? 'text-red-500' : 'text-gray-500'">
                      <!-- Heart Icon -->
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                        class="w-6 h-6 transition-transform duration-200 group-hover:scale-110"
                        :class="isLiked ? 'text-red-500' : 'text-gray-400'"
                      >
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                        2 6 4 4 6.5 4c1.74 0 3.41 1.01 4.13 2.44h1.74
                        C14.09 5.01 15.76 4 17.5 4 20 4 22 6 22 8.5
                        c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                      </svg>
                    </span>
                    <span>{{ likes }}</span>
                  </button>

                  <!-- Comment icon -->
                  <span class="flex items-center gap-1">💬 {{ post.comments.length }}</span>
                </div>

                <div class="mt-6">
                    <input
                    v-model="comment"
                    type="text"
                    placeholder="Add a comment..."
                    class="w-full border rounded px-4 py-2"
                    />

                    <button
                    @click="submitComment" :disabled="!comment"
                    class="mt-2 px-4 py-2 bg-purple-500 text-white rounded"
                    >
                    Post
                    </button>
                </div>
                <div class="mt-6 space-y-6">

                  <div v-for="c in commentData"
                    :key="c.id"
                    class="flex gap-3 items-start"
                  >

                  <!-- Avatar -->
                  <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-bold">
                    {{ c.user.name.charAt(0) }}
                  </div>

                  <!-- Comment Box -->
                  <div class="flex-1">

                    <!-- Username -->
                    <p class="font-semibold text-sm">
                      {{ c.user.name }}
                    </p>

                    <!-- If editing -->
                    <div v-if="editingId === c.id" class="mt-2">
                      <input
                        v-model="editText"
                        type="text"
                        class="w-full border rounded px-4 py-2 mb-2"
                        placeholder="Edit comment..."
                      />
                      <div class="flex gap-2">
                        <button
                          @click="updateComment"
                          class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600"
                        >
                          Update
                        </button>
                        <button
                          @click="cancelEdit"
                          class="px-3 py-1 bg-gray-400 text-white text-xs rounded hover:bg-gray-500"
                        >
                          Cancel
                        </button>
                      </div>
                    </div>

                    <!-- Normal view -->
                    <p v-else class="text-gray-700 mt-1">
                      {{ c.content }}
                    </p>

                    <!-- Actions -->
                    <div class="flex gap-4 text-xs text-gray-500 mt-2">

                      <span>
                        {{ new Date(c.created_at).toLocaleDateString() }}
                      </span>


                      <!-- Only owner sees edit -->
                      <button
                        v-if="authUserId === c.user_id"
                        @click="startEdit(c)"
                        class="hover:text-blue-500"
                      >
                        Edit
                      </button>

                    </div>

                  </div>
                </div>

              </div>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>

</template>
