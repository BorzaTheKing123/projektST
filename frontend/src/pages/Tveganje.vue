<script setup lang="ts">
import { ref, onMounted } from 'vue'
import EventServices from '@/router/services/EventServices'
import ButtonComponent from '../components/buttonComponent.vue'

interface Tveganje {
  id: number
  ime: string
  ukrepi: string
  stranka_id: number
  stranka: {
    name: string
  }
}

const tveganja = ref<Tveganje[]>([])
const isLoading = ref(true)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const response = await EventServices.getTveganja()
    tveganja.value = response.data
  } catch (err) {
    console.error('Napaka pri nalaganju tveganj:', err)
    error.value = 'Ni bilo mogoče naložiti tveganj.'
  } finally {
    isLoading.value = false
  }
})

const dodajTveganje = () => {
  window.location.href = '/tveganja/dodaj'
}

const pojdiNaUrejanje = (tveganja: Tveganje) => {
  window.location.href = `/tveganja/${tveganja.id}`
}
</script>

<template>
  <div class="layout">
    <nav class="sidebar">
      <h2 class="sidebar-title">SBR APLIKACIJA</h2>
      <ul class="sidebar-links">
        <li><router-link to="/stranke" active-class="active">STRANKE</router-link></li>
        <li><router-link to="/tveganja" active-class="active">TVEGANJA</router-link></li>
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
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="tveganja in tveganja"
              :key="tveganja.id"
              @click="pojdiNaUrejanje(tveganja)"
              class="clickable-row"
            >
              <td>{{ tveganja.ime }}</td>
              <td>{{ tveganja.stranka?.name || '—' }}</td>
              <td>{{ tveganja.ukrepi }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else>
        <p>Trenutno ni dodanih tveganj.</p>
      </div>

      <div class="actions-container">
        <ButtonComponent text="DODAJ TVEGANJE" @click="dodajTveganje" />
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

</style>