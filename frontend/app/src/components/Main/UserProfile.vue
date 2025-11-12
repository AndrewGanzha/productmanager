<template>
  <div v-if="user" class="flex flex-col gap-3 defaultBorder size-min p-2">
    <p>Email: {{ user.email }}</p>
    <p>Name: {{ user.name }}</p>
    <p>Role: {{ user.role }}</p>

    <button @click="handleLogout">Выход</button>
  </div>
</template>

<script lang="ts" setup>
import {useUserStore} from "@/stores/user.ts";
import {ref} from "vue";
import {logout} from "@/api/auth/auth.ts";

const userStore = useUserStore();
const user = ref(userStore.user);

const handleLogout = async () => {
  try {
    await logout();
    userStore.clearUser();
  } catch (error) {
    console.error('Logout error:', error);
  }
};
</script>
