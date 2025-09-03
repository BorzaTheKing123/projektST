<script setup>
import { ref, onMounted } from 'vue';
import { RouterLink, RouterView} from 'vue-router'
import { loadAuthToken } from '@/router/services/EventServices'
onMounted(() =>{
// da imas stalno token tudi ce refreshas
loadAuthToken() 

})
</script>

<template>
  <div id="app"></div>

<RouterView></RouterView>
  
</template>


