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
}


const route = useRoute()
const categoryId = route.params.id as string

const loading = ref(false)
const error = ref<string | null>(null)
const articles = ref<Article[]>([])
const categoryName = ref('')
const articleCount = ref(0)

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
    articles.value = Array.isArray(data.articles) ? data.articles : []
  } catch (e: any) {
    error.value = e?.message ?? 'Napaka pri nalaganju kategorije'
    console.error('Napaka pri API klicu:', e)
  } finally {
    loading.value = false
  }
}


onMounted(fetchCategoryData)

const maxIntensity = computed(() =>
  articles.value.length ? Math.max(...articles.value.map(a => a.intensity)) : 1
)

const barWidth = (intensity: number) => {
  const pct = Math.max(5, Math.round((intensity / maxIntensity.value) * 100))
  return `${pct}%`
}

const barColor = (intensity: number) => {
  const t = Math.min(1, intensity / maxIntensity.value)
  if (t < 0.5) {
    const p = t / 0.5
    return mix('#4F46E5', '#F59E0B', p)
  } else {
    const p = (t - 0.5) / 0.5
    return mix('#F59E0B', '#EF4444', p)
  }
}

function mix(a: string, b: string, t: number) {
  const ca = hexToRgb(a)
  const cb = hexToRgb(b)
  const r = Math.round(ca.r + (cb.r - ca.r) * t)
  const g = Math.round(ca.g + (cb.g - ca.g) * t)
  const bl = Math.round(ca.b + (cb.b - ca.b) * t)
  return `rgb(${r}, ${g}, ${bl})`
}

function hexToRgb(hex: string) {
  const v = hex.replace('#', '')
  const bigint = parseInt(v, 16)
  return { r: (bigint >> 16) & 255, g: (bigint >> 8) & 255, b: bigint & 255 }
}
</script>

<template>
  <section class="heatmap">
    <header class="heatmap__header">
      <h2>Kategorija: {{ categoryName }}</h2>
      <p class="subtitle">Število artiklov: {{ articleCount }}</p>
    </header>

    <div v-if="loading" class="state state--loading">Nalagam podatke …</div>
    <div v-else-if="error" class="state state--error">Napaka: {{ error }}</div>

    <div v-else>
      <ul class="article-list">
        <li v-for="(article, idx) in articles" :key="idx">
          <a :href="article.url" target="_blank">{{ article.title || 'Neimenovan članek' }}</a>
          <div class="bar">
            <div
              class="bar__fill"
              :style="{ width: barWidth(article.intensity), backgroundColor: barColor(article.intensity) }"
              :aria-label="`Intenziteta: ${article.intensity}`"
            ></div>
          </div>
        </li>
      </ul>

      <footer class="legend">
        <span class="legend__item"><span class="dot dot--cool"></span> hladno</span>
        <span class="legend__item"><span class="dot dot--warm"></span> toplo</span>
        <span class="legend__item"><span class="dot dot--hot"></span> vroče</span>
      </footer>
    </div>
  </section>
</template>


<style scoped>
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

.article-list li {
  margin-bottom: 1rem;
}

.article-list a {
  font-weight: 600;
  color: #1d4ed8;
  text-decoration: none;
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

.dot--cool { background: #4F46E5; }
.dot--warm { background: #F59E0B; }
.dot--hot  { background: #EF4444; }
</style>