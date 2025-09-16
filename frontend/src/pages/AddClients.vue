<script setup lang="ts">
import { ref } from 'vue'
import EventServices from '@/router/services/EventServices'
import ButtonComponent from '../components/buttonComponent.vue'

const name = ref('')
const email = ref('')
const phone = ref('')
const dejavnost = ref('')
const error = ref<string | null>(null)
const izpis = ref(false)
const napaka = ref('')

const addCustomer = async () => {
  error.value = null

  if (!name.value || !email.value) {
    error.value = 'Polji Ime in Email sta obvezni.'
    return
  }

  const payload = {
    name: name.value,
    email: email.value,
    phone: phone.value,
    dejavnost: dejavnost.value
  }

  try {
    const res = await EventServices.addStranka(payload)
    console.log(res.data)
    alert('Stranka je bila uspešno dodana!')
    window.location.href = '/stranke'
  } catch (err) {
    console.error('Napaka pri dodajanju stranke:', err)
    error.value = 'Prišlo je do napake pri shranjevanju. Prosimo, preverite podatke.'
    setTimeout(() => {
      izpis.value = false
      napaka.value = ''
    }, 5000)
  }
}
</script>

<template>
  <div class="form-card">
    <h1 class="title">Dodaj stranko</h1>

    <div v-if="error" class="error-message">
      {{ error }}
    </div>

    <div class="form-group">
      <input v-model="name" type="text" placeholder="Ime:" />
      <input v-model="email" type="email" placeholder="Email:" />
      <input v-model="phone" type="text" placeholder="Telefonska številka:" />
      <input v-model="dejavnost" type="text" placeholder="Dejavnost:" />
    </div>

    <ButtonComponent
      text="Shrani"
      @click.stop.prevent="addCustomer"
      class="submit-btn"
    />
  </div>
</template>


<style scoped>
/* 5. Vsi spodnji stili so novi ali posodobljeni, da ustrezajo vaši želeni podobi */
.form-card {
  background-color: white;
  padding: 2.5rem 3rem;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 450px; /* Malo širše za daljše vnose */
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  margin: 2rem auto; /* Dodano za centriranje, če je komponenta na svoji strani */
}

.title {
  text-align: center;
  font-size: 2rem;
  color: #333;
  margin: 0; /* Odstranimo privzeto obnašanje */
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 1rem; /* Razmak med vnosnimi polji */
}

input {
  padding: 0.85rem; /* Malo večje za boljši izgled */
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 1rem;
  transition: border-color 0.2s ease;
}

input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
}

.submit-btn {
  background-color: #13b52e;
  color: white;
  border: none;
  padding: 0.85rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s ease;
  width: 100%; /* Gumb naj se razteza čez celo širino */
}

.submit-btn:hover {
  background-color: #069335;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.error-message {
  color: #e74c3c;
  background-color: #fbe2e2;
  border: 1px solid #e74c3c;
  padding: 0.75rem;
  border-radius: 6px;
  text-align: center;
}
</style>