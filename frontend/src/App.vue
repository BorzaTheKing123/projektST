<script setup>
import { ref, onMounted } from 'vue';
import { RouterLink, RouterView} from 'vue-router'
import { loadAuthToken } from '@/router/services/EventServices'
onMounted(() =>{

loadAuthToken() 

})
</script>

<template>
  <div id="app"></div>

<RouterView></RouterView>
  
</template>


