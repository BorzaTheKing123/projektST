<script setup>
import { onMounted } from 'vue';
import { RouterView} from 'vue-router'
import { loadAuthToken } from '@/router/services/EventServices'
import '@/assets/css/base.css';
import '@/assets/css/layout.css';
import '@/assets/css/form.css';
import '@/assets/css/table.css';
import '@/assets/css/buttons.css';
import '@/assets/css/modal.css';
import '@/assets/css/components.css';

onMounted(() =>{
// da imas stalno token tudi ce refreshas
loadAuthToken() 

})
</script>

<template>
  <div id="app"></div>

<RouterView></RouterView>
  
</template>


