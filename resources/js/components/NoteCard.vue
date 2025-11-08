<template>
  <div
    class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 p-5 border flex flex-col"
    :class="[
      note.is_important ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200',
      isHighlighted ? 'ring-4 ring-blue-500 ring-opacity-50 scale-105' : '',
      isCompleted ? 'opacity-75 bg-gray-50' : ''
    ]"
  >
    <div class="flex items-start gap-3 mb-3">
      <button
        @click="$emit('toggle-completed', note.id)"
        class="flex-shrink-0 mt-1 w-5 h-5 rounded border-2 transition-colors flex items-center justify-center"
        :class="isCompleted
          ? 'bg-green-500 border-green-500'
          : 'border-gray-300 hover:border-green-500'"
        :title="isCompleted ? 'Als unerledigt markieren' : 'Als erledigt markieren'"
      >
        <svg
          v-if="isCompleted"
          class="w-4 h-4 text-white"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="3"
            d="M5 13l4 4L19 7"
          />
        </svg>
      </button>

      <div class="flex-1 min-w-0">
        <h3
          class="text-lg font-semibold text-gray-900 break-words"
          :class="{ 'line-through': isCompleted }"
        >
          {{ note.title }}
          <span v-if="note.is_important" class="text-yellow-500 ml-2">⭐</span>
        </h3>
      </div>
    </div>

    <p
      class="text-gray-600 mb-4 flex-1 line-clamp-3 ml-8"
      :class="{ 'line-through': isCompleted }"
    >
      {{ note.content }}
    </p>

    <div class="flex items-center justify-between pt-3 border-t border-gray-200 ml-8">
      <div class="flex flex-col gap-1">
        <span class="text-sm text-gray-500">
          {{ formatDate(note.created_at) }}
        </span>
        <span v-if="isCompleted && note.completed_at" class="text-xs text-green-600">
          ✓ Erledigt: {{ formatDate(note.completed_at) }}
        </span>
      </div>

      <div class="flex gap-2">
        <button
          @click="$emit('edit', note)"
          class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors"
          title="Bearbeiten"
        >
          <svg class="w-5 h-5" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
        </button>

        <button
          @click="$emit('delete', note.id)"
          class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors"
          title="Löschen"
        >
          <svg class="w-5 h-5" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  note: {
    type: Object,
    required: true
  },
  isHighlighted: {
    type: Boolean,
    default: false
  }
});

defineEmits(['edit', 'delete', 'toggle-completed']);

const isCompleted = computed(() => {
  return props.note.is_completed || props.note.completed_at !== null;
});

const formatDate = (dateString) => {
  const date = new Date(dateString);
  const now = new Date();
  const diffInSeconds = Math.floor((now - date) / 1000);

  if (diffInSeconds < 60) {
    return 'Gerade eben';
  } else if (diffInSeconds < 3600) {
    const minutes = Math.floor(diffInSeconds / 60);
    return `vor ${minutes} Minute${minutes !== 1 ? 'n' : ''}`;
  } else if (diffInSeconds < 86400) {
    const hours = Math.floor(diffInSeconds / 3600);
    return `vor ${hours} Stunde${hours !== 1 ? 'n' : ''}`;
  } else if (diffInSeconds < 604800) {
    const days = Math.floor(diffInSeconds / 86400);
    return `vor ${days} Tag${days !== 1 ? 'en' : ''}`;
  } else {
    return date.toLocaleDateString('de-DE', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  }
};
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>

