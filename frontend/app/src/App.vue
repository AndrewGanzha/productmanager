<template>
  <div class="container">
    <div v-if="isInitializing" class="flex items-center justify-center h-screen">
      <div class="text-xl text-gray-600">Загрузка...</div>
    </div>

    <div v-else>
      <UserProfile v-if="isAuthenticated" />
      <RouterView />
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useAuth } from "@/composables/useAuth";
import UserProfile from "@/components/Main/UserProfile.vue";

const { initialize, isAuthenticated } = useAuth();
const isInitializing = ref(true);

onMounted(async () => {
  await initialize();
  isInitializing.value = false;
});
</script>
