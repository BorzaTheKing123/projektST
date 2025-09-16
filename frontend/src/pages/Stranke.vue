<script setup lang="ts">
import ButtonComponent from '../components/buttonComponent.vue'
import { ref, onMounted } from 'vue'
import EventServices from '@/router/services/EventServices'

// Tip za stranko za boljšo preglednost kode
interface Customer {
  id: number
  name: string
  email: string
  phone: string
  dejavnost: string
}

// --- Reaktivne spremenljivke ---
const customers = ref<Customer[]>([])
const isLoading = ref(true)
const error = ref<string | null>(null)

// GET request ob mountu
onMounted(async () => {
  try {
    const response = await EventServices.getStranke()
    customers.value = response.data.original
    console.log('Response:', response)

 // ← tukaj zdaj dejansko uporabimo podatke iz API-ja
  } catch (err) {
    console.error("Prišlo je do napake:", err)
    error.value = "Ni bilo mogoče naložiti podatkov."
  } finally {
    isLoading.value = false
  }
})

// Preusmeritev na urejanje stranke
const pojdiNaUrejanje = (customer: Customer) => {
  window.location.href = `/stranke/${customer.id}`
}

// Preusmeritev na dodajanje stranke
const dodajStranko = () => {
  window.location.href = `/stranke/dodaj`
}
</script>


<template>
  <div class="napis">
    <h1>Seznam strank</h1>

    <div v-if="customers.length > 0">
      <table class="table">
        <thead>
          <tr>
            <th>Ime</th>
            <th>Email</th>
            <th>Telefon</th>
            <th>Dejavnost</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="customer in customers"
            :key="customer.id"
            @click="pojdiNaUrejanje(customer)"
            class="clickable-row"
          >
            <td>{{ customer.name }}</td>
            <td>{{ customer.email }}</td>
            <td>{{ customer.phone }}</td>
            <td>{{ customer.dejavnost }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else-if="!isLoading">
      <p>Trenutno nimate dodanih strank.</p>
    </div>

    <div v-else>
      <p>Nalaganje podatkov...</p>
    </div>

    <div>
      <ButtonComponent
        text="DODAJ STRANKE"
        @click="dodajStranko"
        class="actions-container"
      />
    </div>
  </div>

  <nav class="sidebar">
    <h2 class="sidebar-title">SBR APLIKACIJA</h2>
    <ul class="sidebar-links">
      <li><router-link to="/stranke" active-class="active">STRANKE</router-link></li>
      <li><router-link to="/tveganja" active-class="active">TVEGANJA</router-link></li>
      <li><router-link to="/heatmap" active-class="active">HEATMAP</router-link></li>
    </ul>
  </nav>
</template>


<style scoped>
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
td {
  padding: 1rem 1.5rem;
  color: #2d3748;
  vertical-align: middle;
}
</style>
