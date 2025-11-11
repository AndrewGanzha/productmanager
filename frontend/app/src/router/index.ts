import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/view/Main/index.vue';
import AuthView from '@/view/Auth/index.vue';
import NotfoundView from '@/view/NotFound/index.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', component: HomeView },
    { path: '/auth', component: AuthView },
    { path: '/:pathMatch(.*)*', component: NotfoundView}
  ],
})

export default router
