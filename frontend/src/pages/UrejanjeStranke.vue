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
const tveganja = ref<any[]>([])

const name = ref('')
const email = ref('')
const phone = ref('')
const dejavnost = ref('')
const error = ref<string | null>(null)
const isLoading = ref(true)
const isDeleting = ref(false)
const isOwner = ref(false)
const authUserId = ref<number | null>(null)
const goHome = () => {
  router.push('/stranke')
}

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
    isOwner.value = stranka.user_id === authUserId.value
  } catch (err) {
    error.value = 'Napaka pri nalaganju stranke.'
    console.error('Napaka pri nalaganju stranke:', err)
  }

  try {
    // 3. Pridobi tveganja za to stranko
    const tveganjaRes = await EventServices.getTveganjaZaStranko(customerId.value)
    tveganja.value = tveganjaRes.data
  } catch (err) {
    console.error('Napaka pri nalaganju tveganj:', err)
  } finally {
    isLoading.value = false
  }
})

const goToTveganje = (id: number) => {
  router.push(`/tveganja/${id}`)
}

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
        <h5>Stranka:</h5>
        <input v-model="name" type="text" placeholder="Ime" :disabled="!isOwner" />
        <h5>Email:</h5>
        <input v-model="email" type="email" placeholder="Email" :disabled="!isOwner" />
        <h5>Telefon:</h5>
        <input v-model="phone" type="text" placeholder="Telefon" :disabled="!isOwner" />
        <h5>Dejavnost:</h5>
        <input v-model="dejavnost" type="text" placeholder="Dejavnost" :disabled="!isOwner" />
      </div>
      <br>

      <div class="actions" v-if="isOwner">
        <ButtonComponent text="Shrani spremembe" @click="updateCustomer" class="update-btn" />
        <ButtonComponent text="Izbriši" @click="deleteCustomer" class="delete-btn" />
        <ButtonComponent class="home-btn" text="Nazaj na domačo stran" @click="goHome" />
      </div>
      <div class="tveganja-section">
  <h2>Tveganja za to stranko</h2>


  <div v-if="tveganja.length === 0">Ni tveganj za to stranko.</div>

  <ul v-else>
  <li
    v-for="tveganje in tveganja"
    :key="tveganje.id"
    :class="['tveganje-card', { clickable: isOwner }]"
    @click="isOwner ? goToTveganje(tveganje.id) : null"
  >
    <strong>{{ tveganje.ime }}</strong><br>
    <em>{{ tveganje.ukrepi }}</em>
    <span v-if="!isOwner" class="locked-note">Samo za ogled 🔒</span>
  </li>
</ul>

</div>
</div>
</div>
</template>

<style scoped>
.input{
  margin-top: 0px;
}

.update-btn:not(:disabled):hover {
  background-color: #0f6815;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.delete-btn:not(:disabled){
  background-color: #d32424;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}
.delete-btn:not(:disabled):hover {
  background-color: #b12929;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
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
const goHome = () => {
  router.push('/stranke')
}
<ButtonComponent class="home-btn" text="Nazaj na domačo stran" @click="goHome" />
.home-btn {
  background-color: rgb(140, 142, 140);
  border-color: gray;
}
.home-btn:hover{
  background-color: rgb(64, 67, 64) !important;
  border-color: gray !important;
}