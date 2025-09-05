<script setup lang="ts">
import { ref , onMounted } from 'vue'
import EventServices, { loadAuthToken } from '@/router/services/EventServices'
import ButtonComponent from '../components/buttonComponent.vue'

const ime = ref('')
const ukrepi = ref('')
const error = ref<string | null>(null)
const izpis = ref(false)
const napaka = ref('')
const stranke = ref<{ id: number; name: string }[]>([])
const selectedStrankaId = ref<number | null>(null)

onMounted(async () => {
    loadAuthToken()
  try {
    const res = await EventServices.getStranke()
    stranke.value = res.data.original
  } catch (err) {
    console.error('Napaka pri nalaganju strank:', err)
  }
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
      <input v-model="ime" type="text" placeholder="Ime tveganja:" />
      <select v-model="selectedStrankaId">
  <option disabled value="">Izberi stranko</option>
  <option v-for="stranka in stranke" :key="stranka.id" :value="stranka.id">
    {{ stranka.name }}
  </option>
</select>

      <textarea v-model="ukrepi" placeholder="Ukrepi:" rows="4" />
    </div>

    <ButtonComponent
      text="Shrani"
      @click.stop.prevent="addTveganje"
      class="submit-btn"
    />
  </div>
  <pre>{{ ime }}</pre>
<pre>{{ ukrepi }}</pre>
<pre>{{ selectedStrankaId }}</pre>


</template>

<style scoped>
.form-card {
  background-color: white;
  padding: 2.5rem 3rem;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 450px;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  margin: 2rem auto;
}

.title {
  text-align: center;
  font-size: 2rem;
  color: #333;
  margin: 0;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

input,
textarea {
  padding: 0.85rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 1rem;
  transition: border-color 0.2s ease;
}

input:focus,
textarea:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
}

.submit-btn {
  background-color: #13b52e;
  color: white;
  border: none;
  padding: 0.85rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s ease;
  width: 100%;
}

.submit-btn:hover {
  background-color: #069335;
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.error-message {
  color: #e74c3c;
  background-color: #fbe2e2;
  border: 1px solid #e74c3c;
  padding: 0.75rem;
  border-radius: 6px;
  text-align: center;
}
select {
  padding: 0.85rem;
  border: 1px solid #ccc; /* rahlo siv rob */
  border-radius: 6px;
  font-size: 1rem;
  background-color: #fff;
  color: #2d3748;
  transition: border-color 0.2s ease;
}

select:focus {
  outline: none;
  border-color: #667eea; /* modra ob fokusu */
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
}

</style>
