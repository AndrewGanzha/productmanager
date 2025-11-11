import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import type {User} from "@/api/auth/type";

export const useUserStore = defineStore('user', () => {
  const user = ref<User | null>(null);

  function setUser(newUser: User) {
    user.value = JSON.parse(JSON.stringify(newUser));
  }

  return { user, setUser }
})
