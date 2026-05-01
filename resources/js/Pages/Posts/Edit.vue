<script setup>
import { Link, Head } from '@inertiajs/vue3';
import { Form } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, reactive } from 'vue'

const props = defineProps(['post'])

const form = ref({
  title: props.post.title,
  body: props.post.body,
  image: props.post.image
})

const localErrors = reactive({
    title: '',
    body: '',
    image: null,
});

function handleFile(e) {
    form.value.image = e.target.files[0]
}

function handleSubmit(submit){
    // clear errors
    localErrors.title = ''
    localErrors.body = ''
    localErrors.image = null

    if (!form.value.title) {
        localErrors.title = 'Title is required'
    }

    if (!form.value.body) {
        localErrors.body = 'Description is required'
    }

    if (!form.value.image) {
        localErrors.image = 'Image is required'
    }

    // 🚨 stop if errors exist
    console.log(Object.values(localErrors));
    if (Object.values(localErrors).some(val => val)) {
        return
    }

    submit()

}
const existingImage = props.post.image
</script>

<template>
    <Head title="Edit Post" />
    <AuthenticatedLayout>

    <template #header>
        <h1 class="text-xl font-semibold">Edit Post</h1>
    </template>

    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <Link href="/post/index" class="text-blue-600 mb-4 inline-block">
                ← Back
            </Link>

            <Form :action="`/post/update/${props.post.id}`" method="post" #default="{ submit, processing, errors, wasSuccessful, isDirty }">
                <label class="form-lable">Title</label>
                <input type="text" class="form-input mb-3" name="title" v-model="form.title"/>

               <div v-if="localErrors.title || errors.title" class="form-error">
                    {{ localErrors.title || errors.title }}
                </div>

                <label class="form-lable">Description</label>
                <textarea row="5" class="form-input mb-3" name="body" v-model="form.body" />

                <div v-if="localErrors.body || errors.body" class="form-error">
                    {{ localErrors.body || errors.body }}
                </div>

                <label class="form-lable">Upload Image</label>
                <input type="file" class="form-input mb-5" name="image"  @change="handleFile"/>

                <!-- otherwise show old image -->
                <img v-if="existingImage" :src="`/storage/${existingImage}`" class="w-20 h-20 object-cover"/>

                <div v-if="localErrors.image || errors.image" class="form-error">
                    {{ localErrors.image || errors.image }}
                </div>

                <div class="flex justify-end">
                    <button @click.prevent="handleSubmit(submit)" :disabled="processing || !isDirty" class="btn-primary">
                    {{ processing ? 'Creating...' : 'Update Post' }}
                </button>
                </div>
                <div v-if="wasSuccessful" class="form-success">Post Updated successfully!</div>
            </Form>
        </div>
    </div>
    </AuthenticatedLayout>

</template>
