<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import ButtonComponent from '../components/buttonComponent.vue'

// Props od Inertia/Laravel strani
interface Customer {
  id: number;
  user_id: number;
  name: string;
  email: string;
  phone: string;
  dejavnost: string;
}

const props = defineProps({
  id: { type: [Number, String], required: true },
  stranka: {
    type: Object as () => Customer,
    required: true
  }
})

// reaktivna polja
const customerId = ref<number | null>(null)
const name = ref('')
const email = ref('')
const phone = ref('')
const dejavnost = ref('')
const error = ref<string | null>(null)
const isLoading = ref(true)
const izpis = ref(false)
const napaka = ref('') 

// --- Naložimo obstoječo stranko ob mountu ---
onMounted(() => {
  try {
    customerId.value = props.stranka.id
    name.value = props.stranka.name
    email.value = props.stranka.email
    phone.value = props.stranka.phone
    dejavnost.value = props.stranka.dejavnost
  } catch (err) {
    console.error("Napaka pri nalaganju stranke:", err)
    error.value = "Ni bilo mogoče naložiti podatkov o stranki."
    setTimeout(() => {
  izpis.value = false;
  napaka.value = '';
}, 5000); 
  } finally {
    isLoading.value = false
  }
})

// --- Posodobi obstoječo stranko ---
const updateCustomer = async () => {
  if (!name.value) return
  error.value = null

  try {
    await axios.put(`/${props.id}/stranke/${name.value}`, {
      name: name.value,
      email: email.value,
      phone: phone.value,
      dejavnost: dejavnost.value,
    })
    window.location.href = `/${props.id}/stranke`
    alert('Stranka je bila uspešno posodobljena!')
  } catch (err) {
    console.error("Napaka pri posodobitvi:", err)
    error.value = "Prišlo je do napake pri shranjevanju."
    setTimeout(() => {
  izpis.value = false;
  napaka.value = '';
}, 5000); 
  }
}

// --- Izbriši stranko ---
const isDeleting = ref(false) // prepreči podvajanje dialoga

const deleteCustomer = async () => {
  if (!name.value || isDeleting.value) return // prepreči večkratni trigger

  isDeleting.value = true // nastavimo pred dialogom

  const confirmed = confirm(`Ali res želite izbrisati stranko ${name.value}?`)
  if (!confirmed) {
    isDeleting.value = false // resetiramo, če uporabnik zapre/cancel
    return
  }

  try {
    await axios.delete(`/${props.id}/stranke/${name.value}`)
    alert('Stranka je bila uspešno izbrisana!') // alert pred redirect
    window.location.href = `/${props.id}/stranke`
  } catch (err) {
    console.error("Napaka pri brisanju:", err)
    error.value = "Prišlo je do napake pri brisanju stranke."
    setTimeout(() => {
      error.value = null
    }, 5000)
  } finally {
    isDeleting.value = false // vedno resetiramo na koncu
  }
}
</script>


<template>
  <div class="form-card">
    <h1 class="title">Uredi stranko</h1>

    <div v-if="isLoading">Nalaganje...</div>
    <div v-else>
      <div v-if="error" class="error-message">{{ error }}</div>
      <br>

      <div class="form-group">
        <input v-model="name" type="text" placeholder="Ime" :disabled="id!=stranka.user_id" />
        <input v-model="email" type="email" placeholder="Email" :disabled="id!=stranka.user_id" />
        <input v-model="phone" type="text" placeholder="Telefon" :disabled="id!=stranka.user_id" />
        <input v-model="dejavnost" type="text" placeholder="Dejavnost" :disabled="id!=stranka.user_id" />
      </div>

      <div class="actions">
        <ButtonComponent text="Shrani spremembe" @click="updateCustomer" class="update-btn" :disabled="id!=stranka.user_id" />
        <ButtonComponent text="Izbriši" @click="deleteCustomer" class="delete-btn" :disabled="id!=stranka.user_id" />
      </div>
    </div>
  </div>
</template>

<style scoped>
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
.update-btn:not(:disabled):hover {
  background-color: #0f6815;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.delete-btn {
  background: #e53e3e;
  color: white;
}
.delete-btn:not(:disabled):hover {
  background-color: #b12929;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}
</style>