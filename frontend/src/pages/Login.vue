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
const user = ref(null)
const router = useRouter()

const submitForm = () => {
  if (!email.value || !password.value) {
    napaka.value = 'Prosimo, izpolnite vsa polja.'
    izpis.value = true
    return
  }

  EventServices.getLogin({
    email: email.value,
    password: password.value
  })
    .then((response) => {
      user.value = response.data
      izpis.value = false
      router.push('/stranke') // ali kamor želiš
    })
    .catch((error) => {
      napaka.value = 'Napaka pri prijavi'
      izpis.value = true
      setTimeout(() => {
        izpis.value = false
        napaka.value = ''
      }, 4000)
    })
}
</script>

<template>
  <div class="login">
    <h1>Login</h1>
  </div>
  <div class="input">
    <InputComponent v-model="email" namen="email" />
    <InputComponent v-model="password" namen="password" />
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
.input{

  max-width: 300px;
  margin: auto;
  display: flex;
  flex-direction: column;

}
</style>
