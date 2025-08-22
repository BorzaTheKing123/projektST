<script setup lang="ts">
import ButtonComponent from '../buttonComponent.vue'
import { ref, onMounted } from 'vue'
import axios from 'axios'

// Sprejem 'id' props-a, ki ga pošlje Laravel/Inertia
const props = defineProps({
  id: {
    type: [Number, String], // Tip je lahko število ali niz
    required: true
  },
  stranke: {
    type: Array as () => Customer[], // Ta del specificira da bodo oblike kot je Customer
    default: () => [] // <--- The fix: provide a default empty array
  }
})
const pojdiNaUrejanje = (customer: Customer) => {
  window.location.href = `/${props.id}/stranke/${customer.name}`
}

// Tip za stranko za boljšo preglednost kode
interface Customer {
  id: number;
  name: string;
  email: string;
  phone: string;
  dejavnost: string;
}

// --- NOVE REAKTIVNE SPREMENLJIVKE ---
const customers = ref<Customer[]>([])      // Seznam strank z uporabo vmesnika Customer
const isLoading = ref(true)               // Status nalaganja
const error = ref<string | null>(null)    // Napaka, če pride do nje


// GET request ob mountu
onMounted(() => {
  const url = `/${props.id}/stranke`
  console.log(`Pošiljam GET zahtevek na: ${url}`)
  console.log(props.stranke)

  axios.get(url)
    .then(response => {
      customers.value = props.stranke
    })
    .catch(err => {
      console.error("Prišlo je do napake:", err)
      error.value = "Ni bilo mogoče naložiti podatkov."
    })
    .finally(() => {
      isLoading.value = false
    })
})

const dodajStranko = () => {
  window.location.href = `${window.location.pathname}/dodaj`
}


</script>

<template>
  <div class="napis">
    <h1>Seznam strank</h1>

    <div v-if="customers.length > 0">
      <table>
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
            class="clickable-row">
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
      <ButtonComponent text="DODAJ STRANKE" @click="dodajStranko"  class="actions-container"></ButtonComponent>
    </div>
  </div>

</template>


<style scoped>
.napis {
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

/* ----- Stil Tabele ----- */
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

/* Zebra striping - za boljšo berljivost */
tbody tr:nth-child(even) {
  background-color: #fdfdfe;
}

/* Hover efekt na vrsticah */
tbody tr:hover {
  background-color: #f0f4ff;
}

td {
  padding: 1rem 1.5rem;
  color: #2d3748;
  vertical-align: middle;
}

/* ----- Gumb in Akcije ----- */
.actions-container {
  display: flex;
  justify-content: flex-end;
  margin-top: 2rem;
}
.actions-container:hover {
  background-color: #267640;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);

}

.clickable-row {
  cursor: pointer;
}

.clickable-row:hover {
  background-color: #edf2f7;
}
</style>
