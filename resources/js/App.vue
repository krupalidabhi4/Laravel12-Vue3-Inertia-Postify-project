<template>
  <div>
    <nav class="p-4 bg-gray-800 text-white flex gap-4">
      <button @click="page = 'home'">Home</button>
    </nav>

    <div class="p-6">
      <Home v-if="page === 'home'" />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Home from './components/Home.vue'

const page = ref('home')
</script>