<script setup>
import { RouterLink, RouterView} from 'vue-router'

</script>

<template>
  <div id="app"></div>

  <div class="options">
    <h1 class="napis">Register</h1>
    <RouterLink :to="{ name: 'Register' }" class="btn">Register</RouterLink>
  </div>

  <br><br>

  <div class="options">
    <h1 class="napis">Login</h1>
    <RouterLink to="/login" class="btn">Login</RouterLink>
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
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 10px 20px;
  background-color: #4CAF50;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  font-weight: bold;
  transition: background-color 0.3s ease;
  height: 30px;
  min-width: 120px; 
  font-size: 16px;
  cursor: pointer;
}

.btn:hover {
  background-color: #45a049;
}

</style>
