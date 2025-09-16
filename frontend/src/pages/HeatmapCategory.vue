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
.subtitle{
text-align: left;
}
.state--loading,
.state--error {
  padding: 1rem;
  border-radius: 8px;
  background: #f3f4f6;
  color: #1f2937;
}
</style>