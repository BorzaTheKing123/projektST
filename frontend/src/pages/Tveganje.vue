<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import EventServices from '@/router/services/EventServices'
import ButtonComponent from '../components/buttonComponent.vue'

interface Tveganje {
  id: number
  ime: string
  ukrepi: string
  stranka_id: number
  stranka: {
    name: string
    user_id: number
  }
}

const tveganja = ref<Tveganje[]>([])
const isLoading = ref(true)
const error = ref<string | null>(null)
const router = useRouter()

const aiOdgovor = ref('')
const aiLoading = ref(false)

// AI modal
const showAiModal = ref(false)
const izbranoTveganje = ref<Tveganje | null>(null)
const navodilaZaAi = ref('')

// ID trenutno prijavljenega uporabnika
const authUserId = ref<number | null>(null)

const odpriAiModal = (tveganje: Tveganje) => {
  izbranoTveganje.value = tveganje
  navodilaZaAi.value = ''
  showAiModal.value = true
}

const zapriAiModal = () => {
  showAiModal.value = false
  izbranoTveganje.value = null
  navodilaZaAi.value = ''
}

const posljiAiZahtevek = async () => {
  if (!izbranoTveganje.value) return

  aiLoading.value = true
  aiOdgovor.value = ''

  try {
    const res = await EventServices.posljiAiZahtevek({
      id: izbranoTveganje.value.id,
      ime: izbranoTveganje.value.ime,
      navodila: navodilaZaAi.value
    })

    const zdruzeniUkrepi = res.data.ukrepi
    aiOdgovor.value = res.data.predlogi

    izbranoTveganje.value.ukrepi = zdruzeniUkrepi
  } catch (err) {
    console.error('Napaka pri AI zahtevi:', err)
    aiOdgovor.value = 'Napaka pri pridobivanju predlogov.'
  } finally {
    aiLoading.value = false
    setTimeout(() => {
      zapriAiModal()
    }, 2000)

  }
}

const pojdiNaUrejanje = (tveganje: Tveganje) => {
  router.push(`/tveganja/${tveganje.id}`)
}

const dodajTveganje = () => {
  router.push('/tveganja/dodaj')
}

const razporediTveganje = (ukrepi: string) => {
  // Dodamo preverbo, če so ukrepi morda prazni, da se izognemo napakam
  if (!ukrepi || typeof ukrepi !== 'string') {
    return []
  }
  return ukrepi.split(',')
}

onMounted(async () => {
  try {
    // 1. Pridobi ID trenutnega uporabnika
    const userResponse = await EventServices.getCurrentUser()
    authUserId.value = userResponse.data.id

    // 2. Pridobi vsa tveganja
    const res = await EventServices.getTveganja()
    tveganja.value = res.data

  } catch (err) {
    console.error('Napaka pri nalaganju podatkov:', err)
    error.value = 'Ni bilo mogoče naložiti podatkov.'
  } finally {
    isLoading.value = false
  }
})
</script>

<template>
  <div class="layout">
    <nav class="sidebar">
      <h2 class="sidebar-title">SBR APLIKACIJA</h2>
      <ul class="sidebar-links">
        <li><router-link to="/stranke" active-class="active">STRANKE</router-link></li>
        <li><router-link to="/tveganja" active-class="active">TVEGANJA</router-link></li>
        <li><router-link to="/heatmap" active-class="active">HEATMAP</router-link></li>
      </ul>
    </nav>

    <div class="container">
      <h1>Seznam tveganj</h1>

      <div v-if="isLoading">
        <p>Nalaganje podatkov...</p>
      </div>

      <div v-else-if="error" class="error-message">
        {{ error }}
      </div>

      <div v-else-if="tveganja && tveganja.length > 0">
        <table>
          <thead>
            <tr>
              <th>Ime tveganja</th>
              <th>Stranka</th>
              <th>Ukrepi</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="tveganje in tveganja"
              :key="tveganje.id"
              class="clickable-row"
            >
              <td @click="pojdiNaUrejanje(tveganje)">{{ tveganje.ime }}</td>
              <td @click="pojdiNaUrejanje(tveganje)">{{ tveganje.stranka?.name || '—' }}</td>
              <td @click="pojdiNaUrejanje(tveganje)">
                <div v-for="(ukrep, index) in razporediTveganje(tveganje.ukrepi)" :key="index">
                  {{ ukrep }}
                </div>
              </td>
              <td>
                <div class="ai-cell">
                  <button class="ai-btn" @click.stop="odpriAiModal(tveganje)" :disabled="tveganje.stranka.user_id !== authUserId">AI</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else>
        <p>Trenutno ni dodanih tveganj.</p>
      </div>

      <div>
        <ButtonComponent text="DODAJ TVEGANJE" @click="dodajTveganje" class="actions-container"/>
      </div>

      <!-- AI Modal -->
      <div v-if="showAiModal" class="modal-overlay" @click.self="zapriAiModal">
        <div class="modal-content">
          <button class="close-btn" @click="zapriAiModal">×</button>

          <h3>AI analiza tveganja</h3>
          <p><strong>Stranka:</strong> {{ izbranoTveganje?.stranka?.name || '—' }}</p>
          <p><strong>Tveganje:</strong> {{ izbranoTveganje?.ime }}</p>

          <h4>Dodatna navodila za AI</h4>
          <textarea v-model="navodilaZaAi" placeholder="Vnesi dodatna navodila za AI..." rows="5"></textarea>

          <div v-if="aiLoading">Pridobivam predloge...</div>

          <div v-else-if="aiOdgovor">
            <h4>AI predlogi (samodejno zapisani v ukrepe):</h4>
            <p>{{ izbranoTveganje?.ukrepi }}</p>
          </div>

          <button class="submit-btn" @click="posljiAiZahtevek">Submit</button>
        </div>
      </div>
    </div>
  </div>
</template>



<style scoped>
.submit-btn {
  align-self: flex-end;
  padding: 10px 20px;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.submit-btn:hover {
  background-color: #2980b9;
}
</style>
