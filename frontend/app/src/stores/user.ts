import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import type {User} from "@/api/auth/type";

export const useUserStore = defineStore('user', () => {
  const user = ref<User | null>(null);
  const isLoading = ref<boolean>(false);

  function setUser(newUser: User) {
    user.value = JSON.parse(JSON.stringify(newUser));
  }

  function clearUser() {
    user.value = null;
  }

  function setLoading(loading: boolean) {
    isLoading.value = loading;
  }

  const isAuthenticated = computed(() => user.value !== null);

  return { user, isLoading, setUser, clearUser, setLoading, isAuthenticated }
})
