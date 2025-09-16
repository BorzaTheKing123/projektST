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
.container {
  max-width: 900px;
  margin: 40px auto;
  padding: 2rem;
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

h1 {
  font-size: 2rem;
  font-weight: 700;
  color: #1a202c;
  margin-bottom: 2rem;
  border-bottom: 1px solid #e2e8f0;
  padding-bottom: 1rem;
}

table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

thead {
  background-color: #f7fafc;
  border-bottom: 2px solid #e2e8f0;
}

th {
  padding: 1rem 1.5rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-size: 0.75rem;
  font-weight: 600;
  color: #718096;
}

tbody tr {
  border-bottom: 1px solid #e2e8f0;
  transition: background-color 0.2s ease-in-out;
}

tbody tr:nth-child(even) {
  background-color: #fdfdfe;
}

tbody tr:hover {
  background-color: #f0f4ff;
}

td {
  padding: 1rem 1.5rem;
  color: #2d3748;
  vertical-align: middle;
}

.actions-container {
  display: flex;
  justify-content: flex-end;
  margin-top: 2rem;
}
.actions-container:hover {
  display: flex;
  margin-top: 2rem;
  background-color: forestgreen;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);

}

.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 220px;
  background-color: #2d3748;
  color: #fff;
  padding: 2rem 1rem;
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
}

.sidebar-title {
  font-size: 1.25rem;
  margin-bottom: 2rem;
  font-weight: bold;
  color: #f7fafc;
}

.sidebar-links {
  list-style: none;
  padding: 0;
}

.sidebar-links li {
  margin-bottom: 1rem;
}

.sidebar-links a {
  color: #cbd5e0;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s ease;
}

.sidebar-links a:hover {
  color: #ffffff;
}

.active {
  color: #ffffff;
  font-weight: 700;
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

.clickable-row {
  cursor: pointer;
}

.clickable-row:hover {
  background-color: #edf2f7;
}
.clickable-row td {
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.clickable-row:hover td {
  background-color: #f0f8ff;
}

.ai-btn {
  background-color: #3f85da;
  border: 1px solid #ccc;
  padding: 6px 10px;
  font-weight: bold;
  cursor: pointer;
  border-radius: 4px;
  transition: box-shadow 0.2s ease;
}

.ai-btn:hover {
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}


.modal-content textarea {
  resize: vertical;
  min-height: 100px;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.modal-content button {
  align-self: flex-end;
  padding: 8px 16px;
  background-color: #64b0ee;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.modal-content button:hover {
  background-color: #1b9ae3;
}
.modal-content {
  background: white;
  padding: 30px;
  border-radius: 10px;
  width: 600px;
  max-width: 90%;
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.close-btn {
  position: absolute;
  top: 12px;
  right: 16px;
  background: transparent;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #666;
}

.close-btn:hover {
  color: #000;
}

textarea {
  resize: vertical;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

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
.clickable-row td {
  cursor: pointer;
  transition: background-color 0.2s ease;
  position: relative;
}

.clickable-row:hover td {
  background-color: #f0f8ff;
}

/* AI gumb naj se odzove na svoj hover */
.ai-cell {
  display: flex;
  justify-content: flex-end;
  padding-right: 1rem;
}

.ai-btn:hover {
  background-color: #2c6fc2;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.ai-btn:disabled,
.ai-btn:disabled:hover {
  background-color: #edf2f7;      /* Svetlejša barva besedila */
  cursor: not-allowed;      /* Kazalec, ki označuje, da akcija ni dovoljena */
  box-shadow: none;         /* Odstrani senco ob hoverju */
}

</style>
