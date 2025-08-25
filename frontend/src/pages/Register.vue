<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import InputComponent from '../components/inputComponent.vue'
import ButtonComponent from '../components/buttonComponent.vue'



const name = ref('')
const email = ref('')
const password = ref('')
const napaka = ref('')
const izpis = ref(false)
const user = ref(null)

const router = useRouter()

const submitForm = () => {
  // Preveri, da so vsa polja izpolnjena
  if (!name.value || !email.value || !password.value) {
    napaka.value = 'Prosimo, izpolnite vsa polja.'
    izpis.value = true
    return
  }

  // Pridobi CSRF piškotek
  axios.get('/sanctum/csrf-cookie').then(() => {
    // Pošlji podatke za registracijo
    axios.post('http://localhost:8000/register', {
      name: name.value,
      email: email.value,
      password: password.value
    })
    .then((response) => {
      user.value = response.data
      izpis.value = false

      // 🔁 Redirect na login stran
      router.push('/login')
    })
    .catch((error) => {
      napaka.value = error.response?.data?.message || 'Napaka pri registraciji.'
      izpis.value = true
      console.error('Napaka:', error)
      setTimeout(() => {
      izpis.value = false;
      napaka.value = '';
      }, 4000); 
    })
  })
}
</script>


<template>
  <div class="register">
    <h1>Registracija</h1>
  </div>
  <div class="input">
    <InputComponent v-model="name" namen="name"></InputComponent>
    <InputComponent v-model="email" namen="email"></InputComponent>
    <InputComponent v-model="password" namen="password"></InputComponent>
    <ButtonComponent text="Registriraj se" @click="submitForm"></ButtonComponent>

    <!-- izpišemo napako, če obstaja -->
    <p v-if="izpis">{{ napaka }}</p>
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
  color: rgb(248, 243, 243);
  border-radius: 5px;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}
</style>