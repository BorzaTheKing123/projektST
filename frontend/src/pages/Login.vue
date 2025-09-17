<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import InputComponent from '../components/inputComponent.vue'
import ButtonComponent from '../components/buttonComponent.vue'
import EventServices from '@/router/services/EventServices'

const email = ref('')
const password = ref('')
const napaka = ref('')
const izpis = ref(false)
const router = useRouter()
const goHome = () => {
  router.push('/')
}
const submitForm = async () => {
  try {
    const response = await EventServices.login(email.value, password.value)
    izpis.value = false
    router.push('/stranke')
  } catch (error) {
    napaka.value = 'Napaka pri prijavi'
    izpis.value = true
    setTimeout(() => {
      izpis.value = false
      napaka.value = ''
    }, 4000)
  }
}
</script>

<template>
  <div class="login">
    <h1>Login</h1>
  </div>
  <div class="input">
    <InputComponent v-model="email" namen="email" type="email" />
    <InputComponent v-model="password" namen="password" type="password" />
    <ButtonComponent text="Logiraj se" @click="submitForm" />
    <ButtonComponent class="home-btn" text="Nazaj na domačo stran" @click="goHome" />
    <p v-if="izpis" class="error-message">{{ napaka }}</p>
  </div>
</template>

<style scoped>
.login {
  text-align: center;
  flex-direction: column;
}
.input {
  max-width: 300px;
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

