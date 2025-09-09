import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '../pages/Home.vue'
import Register from '../pages/Register.vue'
import LoginPage from '../pages/Login.vue'
import StrankePage from '../pages/Stranke.vue'
import UrejanjeStrankPage from '../pages/UrejanjeStranke.vue'
import AddClientsPage from '../pages/AddClients.vue'
import TveganjaPage from '../pages/Tveganje.vue'
import DodajTveganjePage from '../pages/DodajTveganje.vue'
import UrediTveganjePage from '../pages/UrejanjeTveganje.vue'
import HeatmapPage from '@/pages/Heatmap.vue'

const URL = ''

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
  {
      path: URL + '/',
      name:'Home',
      component: HomePage,
  },
  {
      path: URL + '/register',
      name:'Register',
      props: true,
      component: Register,
  },
  {
      path: URL + '/login',
      name:'Login',
      props: true,
      component: LoginPage,
  },
  {
      path: URL + '/stranke',
      name:'Stranke',
      props: true,
      component: StrankePage,
  },
  { 
    path: '/stranke/:id',
    name: 'UrejanjeStrank',
    component: UrejanjeStrankPage
  },
  {
      path: URL + '/stranke/dodaj',
      name:'AddClients',
      props: true,
      component: AddClientsPage,
  },
  { 
        path: '/tveganja',
        name: 'Tveganje',
        component: TveganjaPage
  },
  {
        path: '/tveganja/dodaj',
        name: 'DodajTveganje',
         component: DodajTveganjePage,
},
{
        path: '/tveganja/:id',
        name: 'UrediTveganje',
        component: UrediTveganjePage,
        props: true
},
{
    path: '/heatmap',
    name: 'Heatmap',
    component: HeatmapPage,
  },




  ],
})

export default router
