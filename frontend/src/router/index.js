import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '../pages/Home.vue'
import Register from '../pages/Register.vue'
import LoginPage from '../pages/Login.vue'
import StrankePage from '../pages/Stranke.vue'
import UrejanjeStrankPage from '../pages/UrejanjeStranke.vue'
import AddClientsPage from '../pages/AddClients.vue'

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
      path: URL + '/stranke/{name}',
      name:'UrejanjeStrank',
      props: true,
      component: UrejanjeStrankPage,
  },
  {
      path: URL + '/stranke/{name}/dodaj',
      name:'DodajanjeStrank',
      props: true,
      component: AddClientsPage,
  },

  ],
})

export default router
