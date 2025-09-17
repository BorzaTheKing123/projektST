<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import InputComponent from '../components/inputComponent.vue'
import ButtonComponent from '../components/buttonComponent.vue'
import EventServices from '@/router/services/EventServices'

const name = ref('')
const email = ref('')
const password = ref('')
const napaka = ref('')
const izpis = ref(false)
const user = ref(null)

const router = useRouter()
const goHome = () => {
  router.push('/')
}
const submitForm = async () => {
  if (!name.value || !email.value || !password.value) {
    napaka.value = 'Prosimo, izpolnite vsa polja.'
    izpis.value = true
    return
  }
  


  try {
    const response = await EventServices.register(name.value, email.value, password.value)
    user.value = response.data
    izpis.value = false
    router.push('/login')
  } catch (error) {
    napaka.value = error.response?.data?.message || 'Napaka pri registraciji.'
    izpis.value = true
    console.error('Napaka:', error)
    setTimeout(() => {
      izpis.value = false
      napaka.value = ''
    }, 4000)
  }
}
</script>

<template>
  <div class="register">
    <h1 class="title">Registracija</h1>

    <div class="input">
      <InputComponent v-model="name" namen="name" type="name" />
      <InputComponent v-model="email" namen="email" type="email" />
      <InputComponent v-model="password" namen="password" type="password" />
    </div>

    <ButtonComponent class="btn" text="Registriraj se" @click="submitForm" />
    <ButtonComponent class="home-btn" text="Nazaj na domačo stran" @click="goHome" />


    <p v-if="izpis" class="error-message">{{ napaka }}</p>
  </div>
</template>



  
<style scoped>
.register {

  text-align: center;
  max-width: 300px;
  margin: auto;
  display: flex;
  flex-direction: column;
}
.input {
  max-width: 500px;
  min-width: 300px;
  margin: auto;
  display: flex;
  flex-direction: column;
  text-align: left;
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