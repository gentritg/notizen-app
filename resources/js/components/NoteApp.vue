<template>
  <div class="space-y-6 relative">
    <div class="bg-white rounded-lg shadow-sm p-4">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Notizen durchsuchen..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
          />
        </div>

        <div class="flex gap-2 flex-wrap">
          <button
            @click="filterCompleted = null"
            :class="[
              'px-4 py-2 rounded-lg font-medium transition-colors',
              filterCompleted === null
                ? 'bg-blue-600 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
          >
            Alle
          </button>
          <button
            @click="filterCompleted = false"
            :class="[
              'px-4 py-2 rounded-lg font-medium transition-colors',
              filterCompleted === false
                ? 'bg-blue-600 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
          >
            📝 Offen
          </button>
          <button
            @click="filterCompleted = true"
            :class="[
              'px-4 py-2 rounded-lg font-medium transition-colors',
              filterCompleted === true
                ? 'bg-green-600 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
          >
            ✓ Erledigt
          </button>

          <div class="border-l border-gray-300 mx-2"></div>

          <button
            @click="filterImportant = null"
            :class="[
              'px-4 py-2 rounded-lg font-medium transition-colors',
              filterImportant === null
                ? 'bg-gray-300 text-gray-700'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
          >
            Alle
          </button>
          <button
            @click="filterImportant = true"
            :class="[
              'px-4 py-2 rounded-lg font-medium transition-colors',
              filterImportant === true
                ? 'bg-yellow-500 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            ]"
          >
            ⭐ Wichtig
          </button>
        </div>

        <select
          v-model="sortBy"
          class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none bg-white"
        >
          <option value="created_at-desc">Neueste zuerst</option>
          <option value="created_at-asc">Älteste zuerst</option>
          <option value="title-asc">Titel A-Z</option>
          <option value="title-desc">Titel Z-A</option>
          <option value="is_important-desc">Wichtigkeit</option>
        </select>

        <button
          @click="openCreateModal"
          class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors flex items-center gap-2"
        >
          <span class="text-xl">+</span>
          <span class="hidden sm:inline">Neue Notiz</span>
        </button>
      </div>
    </div>

    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4">
      <p class="text-red-800">{{ error }}</p>
    </div>

    <div v-else-if="filteredNotes.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <NoteCard
        v-for="note in filteredNotes"
        :key="note.id"
        :ref="el => noteRefs[note.id] = el"
        :note="note"
        :is-highlighted="highlightedNoteId === note.id"
        @edit="openEditModal"
        @delete="deleteNote"
        @toggle-completed="toggleCompleted"
      />
    </div>

    <div v-else class="bg-white rounded-lg shadow-sm p-12 text-center">
      <div class="text-gray-400 mb-4">
        <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
      </div>
      <h3 class="text-xl font-semibold text-gray-700 mb-2">Keine Notizen gefunden</h3>
      <p class="text-gray-500 mb-6">
        {{ searchQuery || filterImportant !== null ? 'Versuche es mit anderen Suchkriterien' : 'Erstelle deine erste Notiz, um loszulegen!' }}
      </p>
      <button
        v-if="!searchQuery && filterImportant === null"
        @click="openCreateModal"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
      >
        Erste Notiz erstellen
      </button>
    </div>

    <NoteModal
      v-if="showModal"
      :note="editingNote"
      :is-editing="isEditing"
      @close="closeModal"
      @save="saveNote"
    />

    <ToastNotification
      v-for="toast in toasts"
      :key="toast.id"
      :message="toast.message"
      :type="toast.type"
      :duration="toast.duration"
      @close="removeToast(toast.id)"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import axios from 'axios';
import NoteCard from './NoteCard.vue';
import NoteModal from './NoteModal.vue';
import ToastNotification from './ToastNotification.vue';

const notes = ref([]);
const loading = ref(true);
const error = ref(null);
const searchQuery = ref('');
const filterImportant = ref(null);
const filterCompleted = ref(null);
const sortBy = ref('created_at-desc');
const showModal = ref(false);
const editingNote = ref(null);
const isEditing = ref(false);
const toasts = ref([]);
const highlightedNoteId = ref(null);
const noteRefs = ref({});
let toastIdCounter = 0;

const filteredNotes = computed(() => {
  let filtered = notes.value;

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(note =>
      note.title.toLowerCase().includes(query) ||
      note.content.toLowerCase().includes(query)
    );
  }

  if (filterImportant.value !== null) {
    filtered = filtered.filter(note => note.is_important === filterImportant.value);
  }

  if (filterCompleted.value !== null) {
    filtered = filtered.filter(note => {
      const isCompleted = note.completed_at !== null;
      return isCompleted === filterCompleted.value;
    });
  }

  const [sortField, sortOrder] = sortBy.value.split('-');
  filtered = [...filtered].sort((a, b) => {
    let aVal = a[sortField];
    let bVal = b[sortField];

    if (sortField === 'created_at') {
      aVal = new Date(aVal);
      bVal = new Date(bVal);
    } else if (typeof aVal === 'string') {
      aVal = aVal.toLowerCase();
      bVal = bVal.toLowerCase();
    }

    if (sortOrder === 'asc') {
      return aVal > bVal ? 1 : aVal < bVal ? -1 : 0;
    }
    return aVal < bVal ? 1 : aVal > bVal ? -1 : 0;
  });

  return filtered;
});

const fetchNotes = async () => {
  try {
    loading.value = true;
    error.value = null;
    const response = await axios.get('/api/notes');
    notes.value = response.data.data;
  } catch (err) {
    error.value = 'Fehler beim Laden der Notizen';
    console.error('Error fetching notes:', err);
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  editingNote.value = null;
  isEditing.value = false;
  showModal.value = true;
};

const openEditModal = (note) => {
  editingNote.value = { ...note };
  isEditing.value = true;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingNote.value = null;
  isEditing.value = false;
};

const saveNote = async (noteData) => {
  try {
    let response;
    if (isEditing.value) {
      response = await axios.put(`/api/notes/${editingNote.value.id}`, noteData);
      showToast(`Notiz "${noteData.title}" wurde aktualisiert`, 'success');
    } else {
      response = await axios.post('/api/notes', noteData);
      showToast(`Notiz "${noteData.title}" wurde erstellt`, 'success');
    }
    
    await fetchNotes();
    closeModal();

    if (response.data.data) {
      await highlightAndScrollToNote(response.data.data.id);
    }
  } catch (err) {
    console.error('Error saving note:', err);
    showToast('Fehler beim Speichern der Notiz', 'error');
  }
};

const deleteNote = async (noteId) => {
  const noteToDelete = notes.value.find(n => n.id === noteId);
  const noteTitle = noteToDelete ? noteToDelete.title : 'Notiz';

  if (!confirm('Möchtest du diese Notiz wirklich löschen?')) {
    return;
  }

  try {
    await axios.delete(`/api/notes/${noteId}`);
    await fetchNotes();
    showToast(`Notiz "${noteTitle}" wurde gelöscht`, 'success');
  } catch (err) {
    console.error('Error deleting note:', err);
    showToast('Fehler beim Löschen der Notiz', 'error');
  }
};

const showToast = (message, type = 'success', duration = 4000) => {
  const id = toastIdCounter++;
  toasts.value.push({ id, message, type, duration });
};

const removeToast = (id) => {
  toasts.value = toasts.value.filter(toast => toast.id !== id);
};

const highlightAndScrollToNote = async (noteId) => {
  await nextTick();
  
  highlightedNoteId.value = noteId;
  
  await nextTick();
  
  const noteElement = noteRefs.value[noteId];
  if (noteElement && noteElement.$el) {
    noteElement.$el.scrollIntoView({ 
      behavior: 'smooth', 
      block: 'center' 
    });
  }
  
  setTimeout(() => {
    highlightedNoteId.value = null;
  }, 5000);
};

const toggleCompleted = async (noteId) => {
  try {
    const response = await axios.patch(`/api/notes/${noteId}/toggle-completed`);
    
    const noteIndex = notes.value.findIndex(n => n.id === noteId);
    if (noteIndex !== -1) {
      notes.value[noteIndex] = response.data.data;
    }
    
    const isCompleted = response.data.data.completed_at !== null;
    showToast(
      isCompleted 
        ? `Notiz "${response.data.data.title}" als erledigt markiert ✓` 
        : `Notiz "${response.data.data.title}" wieder geöffnet`,
      'success'
    );
  } catch (err) {
    console.error('Error toggling completed:', err);
    showToast('Fehler beim Aktualisieren der Notiz', 'error');
  }
};

onMounted(() => {
  fetchNotes();
});
</script>
