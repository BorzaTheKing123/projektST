
<script setup lang="ts">
import InputComponent from '../components/inputComponent.vue'
import ButtonComponent from '../components/buttonComponent.vue'







import { ref } from 'vue'
import axios from 'axios'

const email = ref('')
const password = ref('')
const user = ref('')
const napaka = ref('')
const izpis = ref(false)

const submitForm = () => {
  axios.get('/sanctum/csrf-cookie').then(() => {
    axios.post('/login', {
      email: email.value,
      password: password.value
    })
    .then((response) => {
      user.value = response.data
      izpis.value = false
    })
    .catch((error) => {
      napaka.value = 'Napaka pri prijavi'
      izpis.value = true
      console.log(error)
      setTimeout(() => {
      izpis.value = false;
      napaka.value = '';
      }, 4000); 
    })
  })
}
</script>



<template>
  <div class="login">
    <h1>Login</h1>
  </div>
  <div class="input">
    <InputComponent v-model="email" namen="email"></InputComponent>
    <InputComponent v-model="password" namen="password"></InputComponent>
    <ButtonComponent text="Logiraj se" @click="submitForm"></ButtonComponent>
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

button {
  padding: 8px;
  background-color: #4CAF50;
  border: none;
  color: white;
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}
</style>

