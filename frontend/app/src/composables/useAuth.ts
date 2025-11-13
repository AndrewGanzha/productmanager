import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useUserStore } from '@/stores/user';
import { login as apiLogin, logout as apiLogout, register as apiRegister, getUser } from '@/api/auth/auth';
import type {RegisterData} from "@/api/auth/type";

export function useAuth() {
  const userStore = useUserStore();
  const router = useRouter();

  const user = computed(() => userStore.user);
  const isAuthenticated = computed(() => userStore.isAuthenticated);
  const isLoading = computed(() => userStore.isLoading);

  const initialize = async (): Promise<boolean> => {
    userStore.setLoading(true);
    try {
      const userData = await getUser();
      if (userData) {
        userStore.setUser(userData);
        return true;
      }
      return false;
    } catch (error) {
      console.error('Failed to initialize auth:', error);
      return false;
    } finally {
      userStore.setLoading(false);
    }
  };

  const login = async (email: string, password: string): Promise<void> => {
    userStore.setLoading(true);
    try {
      const userData = await apiLogin(email, password);
      userStore.setUser(userData);
      await router.push('/');
    } finally {
      userStore.setLoading(false);
    }
  };

  const register = async (data: RegisterData): Promise<void> => {
    userStore.setLoading(true);
    try {
      const userData = await apiRegister(data);
      userStore.setUser(userData);
      await router.push('/');
    } finally {
      userStore.setLoading(false);
    }
  };

  const logout = async (): Promise<void> => {
    userStore.setLoading(true);
    try {
      await apiLogout();
      userStore.clearUser();
      await router.push('/auth');
    } catch (error) {
      console.error('Logout error:', error);
      // Даже при ошибке очищаем пользователя и редиректим
      userStore.clearUser();
      await router.push('/auth');
    } finally {
      userStore.setLoading(false);
    }
  };

  return {
    user,
    isAuthenticated,
    isLoading,
    initialize,
    login,
    register,
    logout
  };
}
