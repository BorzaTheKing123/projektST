import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '../pages/Home.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path:'@/../backend',
      name:'Home',
      component: HomePage,
  }

  ],
})

export default router
