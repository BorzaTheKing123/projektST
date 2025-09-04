<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import EventServices from '@/router/services/EventServices'
import ButtonComponent from '../components/buttonComponent.vue'

interface Tveganje {
  id: number
  stranka_id: number
  ime: string
  ukrepi: string
  stranka: {
    name: string
  }
}

const route = useRoute()
const router = useRouter()
const tveganjeId = ref(Number(route.params.id))

const ime = ref('')
const ukrepi = ref('')
const strankaName = ref('')
const error = ref<string | null>(null)
const isLoading = ref(true)
const isDeleting = ref(false)

onMounted(async () => {
  try {
    const response = await EventServices.getTveganje(tveganjeId.value)
    const tveganje: Tveganje = response.data

    ime.value = tveganje.ime
    ukrepi.value = tveganje.ukrepi
    strankaName.value = tveganje.stranka.name
  } catch (err) {
    error.value = 'Napaka pri nalaganju tveganja.'
    console.error(err)
  } finally {
    isLoading.value = false
  }
})

const updateTveganje = async () => {
  try {
    await EventServices.updateTveganje(tveganjeId.value, {
      ime: ime.value,
      ukrepi: ukrepi.value,
      stranka_id: tveganjeId.value // če je potrebno, sicer odstrani
    })

    alert('Tveganje je bilo uspešno posodobljeno!')
    router.push('/tveganja')
  } catch (err) {
    error.value = 'Napaka pri posodobitvi tveganja.'
    console.error(err)
  }
}

const deleteTveganje = async () => {
  if (isDeleting.value) return
  const confirmed = confirm(`Ali res želite izbrisati tveganje "${ime.value}"?`)
  if (!confirmed) return

  isDeleting.value = true
  try {
    await EventServices.deleteTveganje(tveganjeId.value)
    alert('Tveganje je bilo uspešno izbrisano!')
    router.push('/tveganja')
  } catch (err) {
    error.value = 'Napaka pri brisanju tveganja.'
    console.error(err)
  } finally {
    isDeleting.value = false
  }
}
</script>

<template>
  <div class="form-card">
    <h1 class="title">Uredi tveganje</h1>

    <div v-if="isLoading">Nalaganje...</div>
    <div v-else>
      <div v-if="error" class="error-message">{{ error }}</div><br>

      <div class="form-group">
        <input v-model="ime" type="text" placeholder="Ime tveganja" />
        <input v-model="strankaName" type="text" placeholder="Ime stranke" disabled />
        <textarea v-model="ukrepi" placeholder="Ukrepi" rows="4" />
      </div>

      <div class="actions">
        <ButtonComponent text="Shrani spremembe" @click="updateTveganje" class="update-btn" />
        <ButtonComponent text="Izbriši" @click="deleteTveganje" class="delete-btn" />
      </div>
    </div>
  </div>
</template>

<style scoped>
input:disabled {
  background-color: #edf2f7;
  color: #718096;
  cursor: not-allowed;
}

textarea {
  padding: 0.75rem;
  border: 1px solid #cbd5e0;
  border-radius: 8px;
  resize: vertical;
}

.error-message {
  color: #e53e3e;
  background-color: #fff5f5;
  border: 1px solid #feb2b2;
  padding: 0.75rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  font-weight: 500;
}

.form-card {
  max-width: 600px;
  margin: 40px auto;
  padding: 2rem;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 24px rgba(0,0,0,0.08);
}

.title {
  font-size: 1.8rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
  color: #192f5c;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

input {
  padding: 0.75rem;
  border: 1px solid #cbd5e0;
  border-radius: 8px;
}

.actions {
  margin-top: 2rem;
  display: flex;
  gap: 1rem;
}

.update-btn {
  background: #249236;
  color: white;
}

.update-btn:hover {
  background-color: #0f6815;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.delete-btn {
  background: #e53e3e;
  color: white;
}

.delete-btn:hover {
  background-color: #b12929;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}
</style>