<script setup>
import { ref, onMounted } from 'vue';
import { RouterLink, RouterView} from 'vue-router'
import EventServices from './router/services/EventServices';

const odgovor = ref( 'neki ne dela')


onMounted(()=> {
  EventServices.getRegister()
  .then((response)=>{
    odgovor.value = response.data
  })
  .catch((error)=>{
    console.log(error)
  })
  

})


</script>

<template>
  <div id="app"></div>



   <RouterView></RouterView>
  
</template>


