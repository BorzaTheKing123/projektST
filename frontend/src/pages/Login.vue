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
    <p v-if="izpis" class="error-message">{{ napaka }}</p>
  </div>
</template>

<style scoped>
.login {
  text-align: center;
  max-width: 300px;
  margin: auto;
  display: flex;
  flex-direction: column;
}
.input {
  max-width: 300px;
  margin: auto;
  display: flex;
  flex-direction: column;
}
.error-message {
  color: red;
  text-align: center;
  margin-top: 10px;
}
</style>

