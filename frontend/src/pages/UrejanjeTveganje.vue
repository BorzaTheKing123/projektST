<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import EventServices from '@/router/services/EventServices'
import ButtonComponent from '../components/buttonComponent.vue'

interface Tveganje {
  id: number
  ime: string
  ukrepi: string
  stranka: {
    id: number
    name: string
    user_id: number
  }
}

const route = useRoute()
const router = useRouter()
const tveganjaId = ref(Number(route.params.id))

const ime = ref('')
const ukrepi = ref('')
const strankaName = ref('')
const strankaId = ref<number | null>(null)
const error = ref<string | null>(null)
const isLoading = ref(true)
const isDeleting = ref(false)
const isOwner = ref(false)
const authUserId = ref<number | null>(null)
const goHome = () => {
  router.push('/tveganja')
}

onMounted(async () => {
  try {
    // 1. Pridobi prijavljenega uporabnika
    const userResponse = await EventServices.getCurrentUser()
    authUserId.value = userResponse.data.id

    // 2. Pridobi tveganja
    const response = await EventServices.getTveganje(tveganjaId.value)
    const tveganja: Tveganje = response.data

    ime.value = tveganja.ime
    ukrepi.value = tveganja.ukrepi
    strankaName.value = tveganja.stranka.name
    strankaId.value = tveganja.stranka.id

    // 3. Preveri lastništvo stranke
    isOwner.value = tveganja.stranka.user_id === authUserId.value
  } catch (err) {
    error.value = 'Napaka pri nalaganju tveganja.'
    console.error(err)
  } finally {
    isLoading.value = false
  }
})

const updateTveganje = async () => {
  if (!isOwner.value) return

  try {
    await EventServices.updateTveganje(tveganjaId.value, {
      ime: ime.value,
      ukrepi: ukrepi.value,
      stranka_id: strankaId.value
    })

    alert('Tveganje je bilo uspešno posodobljeno!')
    router.push('/tveganja')
  } catch (err) {
    error.value = 'Napaka pri posodobitvi tveganja.'
    console.error(err)
  }
}

const deleteTveganje = async () => {
  if (!isOwner.value || isDeleting.value) return
  const confirmed = confirm(`Ali res želite izbrisati tveganja "${ime.value}"?`)
  if (!confirmed) return

  isDeleting.value = true
  try {
    await EventServices.deleteTveganje(tveganjaId.value)
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
    <h1 class="title">Uredi tveganja</h1>

    <div v-if="isLoading">Nalaganje...</div>
    <div v-else>
      <div v-if="error" class="error-message">{{ error }}</div><br>

      <div class="form-group">
        <h5>Tveganje:</h5>
        <input v-model="ime" type="text" placeholder="Ime tveganja" :disabled="!isOwner" />
        <h5>Stranka:</h5>
        <input v-model="strankaName" type="text" placeholder="Ime stranke" disabled />
        <h5>Ukrepi:</h5>
        <textarea v-model="ukrepi" placeholder="Ukrepi" rows="4" :disabled="!isOwner":class="{ blurred: !isOwner }"
 ></textarea>
      </div>

      <div class="actions" v-if="isOwner">
        <ButtonComponent text="Shrani spremembe" @click="updateTveganje" class="update-btn" />
        <ButtonComponent text="Izbriši" @click="deleteTveganje" class="delete-btn" />
        <ButtonComponent class="home-btn" text="Nazaj na domačo stran" @click="goHome" />
      </div>
    </div>
  </div>
</template>

<style scoped>
.blurred {
  opacity: 0.6;
  cursor: not-allowed;
  background-color: #edf2f7;
  color: #718096;
  padding: 0.75rem;
  border: 1px solid #cbd5e0;
  border-radius: 8px;
}


.home-btn {
  background-color: rgb(140, 142, 140);
  border-color: gray;
}
.home-btn:hover{
  background-color: rgb(64, 67, 64) !important;
  border-color: gray !important;
}


</style>