<template>
  <div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
      <header class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Notizen</h1>
        <p class="text-gray-600">Verwalte deine Notizen einfach und übersichtlich</p>
      </header>

      <NoteApp />
    </div>
  </div>
</template>

<script setup>
import NoteApp from './components/NoteApp.vue';
</script>

