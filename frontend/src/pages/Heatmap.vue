<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import EventServices from '../router/services/EventServices'

type RiskItem = {
  key: string
  category: string
  count: number
}
//namenjeno za teestiranje(moras samo dodati gumb v template in v api.php odkomentirat to pot)
//const runScraper = async () => {
 // try {
 //   const res = await EventServices.runScraper() as Number
//
 //   // Tukaj varno dostopamo do podatkov
 //   alert(`✅ Scraper zagnan! Obdelanih člankov: ${res}`)
 // } catch (e) {
 //   alert('❌ Napaka pri zagonu scraperja')
 //   console.error(e)
 // }
//}

const props = defineProps<{
  apiUrl?: string
  risks?: RiskItem[] | null
  limit?: number
}>()

const limit = computed(() => props.limit ?? 10)
const loading = ref(false)
const error = ref<string | null>(null)
const data = ref<RiskItem[]>(props.risks ?? [])

const fetchData = async () => {
  if (props.risks && props.risks.length) return
  loading.value = true
  error.value = null
  try {
    const res = await EventServices.getTopTveganja(limit.value)
    data.value = Array.isArray(res.data) ? res.data : (res.data.data ?? [])
    console.log(data.value)
  } catch (e: any) {
    error.value = e?.message ?? 'Neznana napaka'
  } finally {
    loading.value = false
  }
}

onMounted(fetchData)
watch(() => props.risks, (val) => {
  if (val) data.value = val
})

const top10 = computed(() =>
  [...data.value].sort((a, b) => b.count - a.count).slice(0, limit.value)
)

const maxCount = computed(() => top10.value[0]?.count ?? 1)

const barWidth = (count: number) => {
  const pct = Math.max(5, Math.round((count / maxCount.value) * 100))
  return `${pct}%`
}

const barColor = (count: number) => {
  const t = Math.min(1, count / maxCount.value)
  if (t < 0.5) {
    const p = t / 0.5
    return mix('#0900a4', '#F59E0B', p)
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
  <div class="layout">
    <nav class="sidebar">
      <h2 class="sidebar-title">SBR APLIKACIJA</h2>
      <ul class="sidebar-links">
        <li><router-link to="/stranke" active-class="active">STRANKE</router-link></li>
        <li><router-link to="/tveganja" active-class="active">TVEGANJA</router-link></li>
        <li><router-link to="/heatmap" active-class="active">HEATMAP</router-link></li>
      </ul>
    </nav>
  </div>

  <section class="heatmap">
    <header class="heatmap__header">
      <h2>Top 10 tveganj</h2>
      <p class="subtitle">Na podlagi LLM analize novic (število prebranih člankov na tveganje)</p>
    </header>

    <div v-if="loading" class="state state--loading">Nalagam podatke …</div>
    <div v-else-if="error" class="state state--error">Napaka: {{ error }}</div>

    <div v-else>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Tveganje</th>
            <th>Članki</th>
            <th class="col-heat">Intenziteta</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, idx) in top10" :key="item.key">
            <td>{{ idx + 1 }}</td>
            <td class="risk-name">
              <router-link :to="`/categories/${item.key}`" class="risk-link">
                {{ item.category }}
              </router-link>
            </td>
            <td class="count">{{ item.count }}</td>
            <td class="heat">
              <div class="bar">
                <div
                  class="bar__fill"
                  :style="{ width: barWidth(item.count), backgroundColor: barColor(item.count) }"
                  :aria-label="`Intenziteta za ${item.category}: ${item.count}`"
                ></div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <footer class="legend">
        <span class="legend__item"><span class="dot dot--cool"></span> hladno</span>
        <span class="legend__item"><span class="dot dot--warm"></span> toplo</span>
        <span class="legend__item"><span class="dot dot--hot"></span> vroče</span>
      </footer>
    </div>
  </section>
</template>




<style scoped>


h2 {
  font-size: 1.1rem;
  margin: 0;
  color: black;
}

.subtitle {
  margin: 0;
  color: #9aa3b2;
  font-size: 0.9rem;
}

.state--error {
  background: #2a0f10;
  color: #ffb4b4;
}
.table th,
.table td {
  padding: 10px 8px;
  border-bottom: 1px solid #1b2340;
}

.active {
  color: #ffffff;
  font-weight: 700;
}
.risk-link {
  color: #1d4ed8;
  text-decoration: none;
  font-weight: 600;
}
.risk-link:hover {
  text-decoration: underline;
}


</style>