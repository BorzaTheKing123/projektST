<script setup lang="ts">
import { ref , onMounted } from 'vue'
import EventServices, { loadAuthToken } from '@/router/services/EventServices'
import ButtonComponent from '../components/buttonComponent.vue'
import { useRoute, useRouter } from 'vue-router'

const ime = ref('')
const ukrepi = ref('')
const error = ref<string | null>(null)
const izpis = ref(false)
const napaka = ref('')
const stranke = ref<any[]>([])
const selectedStrankaId = ref<number | null>(null)
const router = useRouter()
const goHome = () => {
  router.push('/tveganja')
}

onMounted(async () => {
    loadAuthToken()
  const res = await EventServices.getMojeStranke()
  stranke.value = res.data
})


const addTveganje = async () => {
  error.value = null

  if (!ime.value || !selectedStrankaId.value || !ukrepi.value) {
    error.value = 'Vsa polja so obvezna.'
    return
  }

  const payload = {
  ime: ime.value,
  stranka_id: selectedStrankaId.value,
  ukrepi: ukrepi.value
}


  try {
    const res = await EventServices.createTveganje(payload)
    console.log(res.data)
    alert('Tveganje je bilo uspešno dodano!')
    window.location.href = '/tveganja'
  } catch (err) {
    console.error('Napaka pri dodajanju tveganja:', err)
    error.value = 'Prišlo je do napake pri shranjevanju. Prosimo, preverite podatke.'
    setTimeout(() => {
      izpis.value = false
      napaka.value = ''
    }, 5000)
  }
}
</script>

<template>
  <div class="form-card">
    <h1 class="title">Dodaj tveganja</h1>

    <div v-if="error" class="error-message">
      {{ error }}
    </div>

    <div class="form-group">
      <h5>Tveganje:</h5>
      <input v-model="ime" type="text" placeholder="Ime tveganja:" />
      <h5>Stranka:</h5>
      <select v-model="selectedStrankaId" placeholder="Ime stranke:">
        <option v-for="stranka in stranke" :key="stranka.id" :value="stranka.id" >
          {{ stranka.name }}
        </option>
      </select>
      <h5>Ukrepi:</h5>
<textarea v-model="ukrepi" placeholder="Ukrepi:" rows="4" />
    </div>
    <br>

    <ButtonComponent
      text="Shrani"
      @click.stop.prevent="addTveganje"
      class="submit-btn"
    />
    <ButtonComponent class="home-btn" text="Nazaj na domačo stran" @click="goHome" />
  </div>
</template>
<style scoped>
.form-card{
  max-width: 500px;
}
.title{
  text-align: center;
}
.submit-btn{
  width: 100%;
  height: 50px;
}

.home-btn {
  background-color: rgb(140, 142, 140);
  border-color: gray;
  margin-top: 3px;
}
.home-btn:hover{
  background-color: rgb(64, 67, 64) !important;
  border-color: gray !important;
}
</style>

