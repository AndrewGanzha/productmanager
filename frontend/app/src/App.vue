<template>
  <div class="container">
    <RouterView/>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import { getUser } from "@/api/auth/auth.ts";
import {useUserStore} from "@/stores/user.ts";
import {useRouter} from "vue-router";

const userStore = useUserStore();

const router = useRouter();
const isAuth = ref(false);

onMounted(async () => {
  try {
    const userData = await getUser();
    if (userData) {
      isAuth.value = true;
      userStore.setUser(userData);
      router.push('/');
    } else {
      router.push('/auth');
    }
  } catch (error) {
    console.error('Failed to get user:', error);
    isAuth.value = false;
  }
});
</script>
