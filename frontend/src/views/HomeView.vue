<script setup>
import { onMounted, ref } from 'vue'
import { fetchPosts } from '@/services/posts'
import { excerpt } from '@/utils/post'

const posts = ref([])
const loading = ref(true)
const error = ref('')

onMounted(async () => {
  try {
    posts.value = await fetchPosts()
  } catch {
    error.value = 'Catatan tidak dapat dimuat. Pastikan server Laravel sedang berjalan.'
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <main>
    <h1>Catatan Jurnal</h1>

    <p v-if="loading">Memuat catatan...</p>
    <p v-else-if="error" role="alert">{{ error }}</p>
    <p v-else-if="posts.length === 0">Belum ada catatan jurnal.</p>

    <ul v-else>
      <li v-for="post in posts" :key="post.id">
        <h2>{{ post.title }}</h2>
        <p v-if="post.mood">Mood: {{ post.mood }}</p>
        <p>{{ excerpt(post.body) }}</p>
      </li>
    </ul>
  </main>
</template>