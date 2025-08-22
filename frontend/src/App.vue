<script setup>
import { RouterLink, RouterView} from 'vue-router'

</script>

<template>
  <div id="app" ></div>
  <div class="options">
    <h1 class="napis">Register</h1>
<RouterLink to="/register">Register</RouterLink>
  </div>
  <br><br>
  <div class="options">
    <h1 class="napis">Login</h1>
  <RouterLink to="/login">Login</RouterLink>
  </div>


  

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
