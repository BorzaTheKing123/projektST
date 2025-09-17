<script setup lang="ts">
import { ref } from 'vue'
import EventServices from '@/router/services/EventServices'
import ButtonComponent from '../components/buttonComponent.vue'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const phone = ref('')
const dejavnost = ref('')
const error = ref<string | null>(null)
const izpis = ref(false)
const napaka = ref('')
const router = useRouter()
const goHome = () => {
  router.push('/stranke')
}
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
      <h5>Ime:</h5>
      <input v-model="name" type="text" placeholder="Ime:" />
      <h5>Email:</h5>
      <input v-model="email" type="email" placeholder="Email:" />
      <h5>Telefonska številka:</h5>
      <input v-model="phone" type="text" placeholder="Telefonska številka:" />
      <h5>Dejavnost:</h5>
      <input v-model="dejavnost" type="text" placeholder="Dejavnost:" />
    </div>
    <br>

    <ButtonComponent
      text="Shrani"
      @click.stop.prevent="addCustomer"
      class="submit-btn"
    /><br>
    <ButtonComponent class="home-btn" text="Nazaj na domačo stran" @click="goHome" />
  </div>
</template>
<style scoped>
.form-card{
  max-width: 500px;
}
.title{
  text-align: center;
}
.submit-btn{
  width: 100%;
  height: 50px;
}


.home-btn {
  background-color: rgb(140, 142, 140);
  border-color: gray;
  margin-top: 3px;
  
}
.home-btn:hover{
  background-color: rgb(64, 67, 64) !important;
  border-color: gray !important;
}
</style>