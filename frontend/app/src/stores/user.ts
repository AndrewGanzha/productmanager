import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import type {User} from "@/api/auth/type";

export const useUserStore = defineStore('user', () => {
  const user = ref<User | null>(null);

  function setUser(newUser: User) {
    user.value = JSON.parse(JSON.stringify(newUser));
  }

  function clearUser() {
    user.value = null;
  }

  const isAuthenticated = computed(() => user.value !== null);

  return { user, setUser, clearUser, isAuthenticated }
})
