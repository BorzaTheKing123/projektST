<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import EventServices from '@/router/services/EventServices'
import ButtonComponent from '../components/buttonComponent.vue'

interface Customer {
  id: number
  user_id: number
  name: string
  email: string
  phone: string
  dejavnost: string
}

const route = useRoute()
const router = useRouter()
const customerId = ref(Number(route.params.id))

const name = ref('')
const email = ref('')
const phone = ref('')
const dejavnost = ref('')
const error = ref<string | null>(null)
const isLoading = ref(true)
const isDeleting = ref(false)
const isOwner = ref(false)
const authUserId = ref<number | null>(null)

onMounted(async () => {
  try {
    // 1. Pridobi prijavljenega uporabnika
    const userResponse = await EventServices.getCurrentUser()
    authUserId.value = userResponse.data.id

    // 2. Pridobi stranko
    const response = await EventServices.getStranka(customerId.value)
    const stranka: Customer = response.data

    name.value = stranka.name
    email.value = stranka.email
    phone.value = stranka.phone
    dejavnost.value = stranka.dejavnost

    // 3. Preveri lastništvo
    isOwner.value = stranka.user_id === authUserId.value
  } catch (err) {
    error.value = 'Napaka pri nalaganju stranke.'
    console.error(err)
  } finally {
    isLoading.value = false
  }
})

const updateCustomer = async () => {
  if (!isOwner.value) return

  try {
    await EventServices.updateStranka(customerId.value, {
      id: customerId.value,
      name: name.value,
      email: email.value,
      phone: phone.value,
      dejavnost: dejavnost.value
    })

    alert('Stranka je bila uspešno posodobljena!')
    router.push('/stranke')
  } catch (err) {
    error.value = 'Napaka pri posodobitvi stranke.'
    console.error(err)
  }
}

const deleteCustomer = async () => {
  if (!isOwner.value || isDeleting.value) return
  const confirmed = confirm(`Ali res želite izbrisati stranko ${name.value}?`)
  if (!confirmed) return

  isDeleting.value = true
  try {
    await EventServices.deleteStranka(customerId.value)
    alert('Stranka je bila uspešno izbrisana!')
    router.push('/stranke')
  } catch (err) {
    error.value = 'Napaka pri brisanju stranke.'
    console.error(err)
  } finally {
    isDeleting.value = false
  }
}
</script>


<template>
  <div class="form-card">
    <h1 class="title">Uredi stranko</h1>

    <div v-if="isLoading">Nalaganje...</div>
    <div v-else>
      <div v-if="error" class="error-message">{{ error }}</div><br>

      <div class="form-group">
        <input v-model="name" type="text" placeholder="Ime" :disabled="!isOwner" />
        <input v-model="email" type="email" placeholder="Email" :disabled="!isOwner" />
        <input v-model="phone" type="text" placeholder="Telefon" :disabled="!isOwner" />
        <input v-model="dejavnost" type="text" placeholder="Dejavnost" :disabled="!isOwner" />
      </div>

      <div class="actions" v-if="isOwner">
        <ButtonComponent text="Shrani spremembe" @click="updateCustomer" class="update-btn" />
        <ButtonComponent text="Izbriši" @click="deleteCustomer" class="delete-btn" />
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
