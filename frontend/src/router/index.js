import { createRouter, createWebHistory } from 'vue-router'
import AppPage from '../App.vue'
import RegisterPage from '../pages/Register.vue'
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
      component: AppPage,
  },
  {
      path: URL + '/register',
      name:'Register',
      component: RegisterPage,
  },
  {
      path: URL + '/login',
      name:'Login',
      component: LoginPage,
  },
  {
      path: URL + '/stranke',
      name:'Stranke',
      component: StrankePage,
  },
  {
      path: URL + '/stranke/{name}',
      name:'UrejanjeStrank',
      component: UrejanjeStrankPage,
  },
  {
      path: URL + '/stranke/{name}/dodaj',
      name:'DodajanjeStrank',
      component: AddClientsPage,
  },

  ],
})

export default router
