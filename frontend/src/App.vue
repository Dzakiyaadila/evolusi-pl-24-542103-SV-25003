<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="site">
    <header class="site-header">
      <RouterLink class="brand" to="/">Jurnalku</RouterLink>

      <nav aria-label="Navigasi utama">
        <RouterLink to="/">Daftar Jurnal</RouterLink>
        <RouterLink to="/about">Tentang</RouterLink>
      </nav>
    </header>

    <RouterView />
  </div>
</template>