<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import InputComponent from '../inputComponent.vue'
import ButtonComponent from '../buttonComponent.vue'

const name = ref('')
const email = ref('')
const password = ref('')

// reaktivna spremenljivka za izpis napake
const izpis = ref(false)
const napaka = ref('') // tu bomo shranili sporočilo napake

const submitForm = async () => {
  try {
    const res = await axios.post("/register", {
      name: name.value,
      email: email.value,
      password: password.value,
    })
    if (res.data == true){
      window.location.href = '/login'
      izpis.value = true
    } else {
      napaka.value = res.data
      izpis.value = true
      console.log(napaka)
    }
      
  } catch (err) {
   // console.error(err)
    napaka.value = "Prišlo je do napake pri registraciji."
    izpis.value = true
    setTimeout(() => {
  izpis.value = false;
  napaka.value = '';
}, 5000); 
  }
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