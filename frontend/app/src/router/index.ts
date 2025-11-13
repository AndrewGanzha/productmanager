import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/view/Main/index.vue';
import AuthView from '@/view/Auth/index.vue';
import NotfoundView from '@/view/NotFound/index.vue';
import { useUserStore } from '@/stores/user';
import { tokenStorage } from '@/utils/tokenStorage';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: HomeView,
      meta: { requiresAuth: true }
    },
    {
      path: '/auth',
      component: AuthView,
      meta: { guest: true }
    },
    {
      path: '/:pathMatch(.*)*',
      component: NotfoundView
    }
  ],
})

// Navigation guard для защиты роутов
router.beforeEach((to, _from, next) => {
  const userStore = useUserStore();
  const hasToken = tokenStorage.hasToken();
  const isAuthenticated = userStore.isAuthenticated;

  // Если роут требует авторизации
  if (to.meta.requiresAuth) {
    if (!hasToken) {
      // Нет токена - редирект на авторизацию
      next('/auth');
    } else if (!isAuthenticated && !userStore.isLoading) {
      // Есть токен, но пользователь не загружен и не идет загрузка
      // Это может быть при первой загрузке, пропускаем
      next();
    } else if (!isAuthenticated && userStore.isLoading) {
      // Идет загрузка пользователя, пропускаем
      next();
    } else {
      // Все хорошо, пользователь авторизован
      next();
    }
  }
  // Если роут только для гостей (например, страница авторизации)
  else if (to.meta.guest) {
    if (isAuthenticated) {
      // Пользователь уже авторизован, редирект на главную
      next('/');
    } else {
      next();
    }
  }
  // Публичные роуты (например, 404)
  else {
    next();
  }
});

export default router
