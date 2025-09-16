<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import EventServices from '../router/services/EventServices'

type Article = {
  title: string
  url: string
  intensity: number
}
type CategoryResponse = {
  category: string
  article_count: number
  articles: Article[]
  summary: string
}


const route = useRoute()
const categoryId = route.params.id as string

const loading = ref(false)
const error = ref<string | null>(null)
const articles = ref<Article[]>([])
const categoryName = ref('')
const articleCount = ref(0)
const categorySummary = ref('')

const fetchCategoryData = async () => {
  if (!categoryId) {
    error.value = 'Manjka ID kategorije'
    return
  }

  loading.value = true
  error.value = null

  try {
    const res = await EventServices.getCategory(categoryId)
    const data = res.data as CategoryResponse

    categoryName.value = data.category
    articleCount.value = data.article_count
    categorySummary.value = data.summary

    articles.value = Array.isArray(data.articles) ? data.articles : []
  } catch (e: any) {
    error.value = e?.message ?? 'Napaka pri nalaganju kategorije'
    console.error('Napaka pri API klicu:', e)
  } finally {
    loading.value = false
  }
}

// Funkcija za izračun barve na podlagi intenzitete
// Vhod: intensity (število med 0 in 100)
// Izhod: CSS barvni niz (npr. 'rgb(255, 0, 0)' za rdečo, 'rgb(0, 255, 0)' za zeleno)
const getIntensityColor = (intensity: number) => {
  // Omejimo intenziteto med 60 in 100
  const clamped = Math.max(60, Math.min(100, intensity));
  const normalized = (clamped - 60) / 40; // 0 pri 60, 1 pri 100

  let red = 0;
  let green = 0;
  let blue = 0;

  if (normalized < 0.5) {
    // Od rdeče (60) do rumene (80)
    const ratio = normalized * 2; // 0 → 1
    red = 255;
    green = Math.round(255 * ratio); // 0 → 255
    blue = 0;
  } else {
    // Od rumene (80) do temno zelene (100)
    const ratio = (normalized - 0.5) * 2; // 0 → 1
    red = Math.round(255 * (1 - ratio)); // 255 → 0
    green = Math.round(255 * (1 - ratio * 0.6)); // 255 → ~100
    blue = 0;
  }

  return `rgb(${red}, ${green}, ${blue})`;
};



onMounted(fetchCategoryData)
</script>

<template>
  <section class="heatmap">
    <header class="heatmap__header">
      <h2>Kategorija: <span class="highlighted-category">{{ categoryName }}</span></h2>
      <p class="summary">{{ categorySummary }}</p>
      <p class="subtitle">Število artiklov: {{ articleCount }}</p>
    </header>

    <div v-if="loading" class="state state--loading">Nalagam podatke …</div>
    <div v-else-if="error" class="state state--error">Napaka: {{ error }}</div>

    <div v-else>
      <div class="article-list-header">
        <span class="article-list-header__title">Obnova članka</span>
        <span class="article-list-header__intensity">Intenziteta</span>
      </div>

      <ul class="article-list">
        <li v-for="(article, idx) in articles" :key="idx" class="article-list__item">
          <a :href="article.url" target="_blank">{{ article.title || 'Neimenovan članek' }}</a>
          <div class="intensity-display">
            <span 
              class="intensity-dot" 
              :style="{ backgroundColor: getIntensityColor(article.intensity) }"
            ></span>
            {{ article.intensity }}
          </div>
        </li>
      </ul>
    </div>
  </section>
</template>


<style scoped>

  .highlighted-category {
   font-weight: bold;
   color: rgb(54, 11, 171);
  }
  .heatmap {
    background-color: #ffffff;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    max-width: 900px;
    margin: 40px auto;
  }

  .heatmap__header {
    margin-bottom: 1rem;
  }

  .subtitle {
    color: #9aa3b2;
    font-size: 0.9rem;
  }

  .state--loading,
  .state--error {
    padding: 1rem;
    border-radius: 8px;
    background: #f3f4f6;
    color: #1f2937;
  }

  .article-list {
    list-style: none;
    padding: 0;
  }

  .article-list__item { /* Dodan nov razred za li elemente */
    margin-bottom: 1rem;
    display: flex; /* Uporabimo flexbox */
    justify-content: space-between; /* Razmaknemo vsebino */
    align-items: center; /* Poravnamo po sredini navpično */
    padding: 0.5rem 0; /* Malo prostora zgoraj in spodaj */
    border-bottom: 1px solid #eee; /* Ločnica med elementi */
  }

  .article-list__item:last-child {
    border-bottom: none; /* Brez ločnice pri zadnjem elementu */
  }


  .article-list a {
    font-weight: 600;
    color: #1d4ed8;
    text-decoration: none;
    flex-grow: 1; /* Povezava lahko zavzame čim več prostora */
    margin-right: 1rem; /* Presledek med povezavo in intenziteto */
  }

  /* Stil za prikaz intenzitete in kroga */
  .intensity-display {
    display: flex;
    align-items: center;
    gap: 8px; /* Presledek med krogom in številko */
    font-weight: 600;
    color: #333;
    flex-shrink: 0; /* Preprečimo, da bi se intenziteta skrčila */
  }

  .intensity-dot {
    width: 14px; /* Velikost kroga */
    height: 14px;
    border-radius: 50%; /* Naredi krog */
    border: 1px solid rgba(0, 0, 0, 0.1); /* Nežen rob */
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Nežna senca */
  }


  .bar {
    width: 100%;
    height: 12px;
    background: #d5d9e6;
    border-radius: 6px;
    overflow: hidden;
    margin-top: 4px;
  }

  .bar__fill {
    height: 100%;
    border-radius: 6px;
    transition: width 300ms ease, background-color 300ms ease;
  }

  .legend {
    display: flex;
    gap: 16px;
    align-items: center;
    color: #9aa3b2;
    font-size: 0.85rem;
    padding-top: 1rem;
    margin-top: 1rem; /* Dodaj malo prostora nad legendo */
    border-top: 1px solid #eee; /* Ločnica nad legendo */
  }

  .legend__item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .dot {
    width: 10px;
    height: 10px;
    border-radius: 999px;
    display: inline-block;
    border: 1px solid #1f2a4d;
  }

  /* Vsi obstoječi stili ostanejo nespremenjeni, razen spodaj navedenih. */

  /* Stil za glavo seznama */
  .article-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    color: #555;
    border-bottom: 2px solid #ddd;
    padding-bottom: 0.5rem;
    margin-bottom: 0.5rem;
  }

  .article-list-header__title {
    /* Povezava lahko zavzame večino prostora */
    flex-grow: 1;
  }

  .article-list-header__intensity {
    /* Namesto fiksne širine, poravnajte besedilo desno */
    text-align: right;
    /* Dodamo malo prostora levo od besedila, da se ujema s presledkom pri člankih */
    padding-left: 24px; /* Ujemite to vrednost s presledkom v intensity-display */
  }

  /* Prilagoditev obstoječega stila za artikel, da bo poravnava popolna */
  .article-list__item {
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
  }

  .intensity-display {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #333;
    justify-content: flex-end; /* Poravnajte elemente na desno */
  }
  .summary{
    color: #4b4e53;
    font-size: 1.1rem;
  }
</style>