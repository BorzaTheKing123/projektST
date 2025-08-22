<script setup lang="ts">
import ButtonComponent from '../components/buttonComponent.vue'

const goToRegister = () => {
  window.location.href = '/register'
}

const goToLogin = () => {
  window.location.href = '/login'
}
</script>

<template>
  <div class="options">
    <h1 class="napis">Register</h1>
    <ButtonComponent @click="goToRegister" text="Registriraj se" />
  </div>
  <br><br>
  <div class="options">
    <h1 class="napis">Login</h1>
    <ButtonComponent @click="goToLogin" text="Logiraj se" />
  </div>

  <RouterLink to="/login">Login</RouterLink>
  <RouterLink to="/register">Register</RouterLink>

</template>

<style scoped>
.options {
  max-width: 300px;
  margin: auto;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.napis {
  margin: auto;
  gap: 100px;
}
</style>