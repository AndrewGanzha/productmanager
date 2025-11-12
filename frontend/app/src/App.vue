<template>
  <div class="container">
    <!-- Показываем loader пока идет инициализация -->
    <div v-if="isInitializing" class="flex items-center justify-center h-screen">
      <div class="text-xl text-gray-600">Загрузка...</div>
    </div>

    <!-- После инициализации показываем контент -->
    <RouterView v-else />
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useAuth } from "@/composables/useAuth";

const { initialize } = useAuth();
const isInitializing = ref(true);

onMounted(async () => {
  // Инициализируем приложение - проверяем авторизацию
  await initialize();
  isInitializing.value = false;
});
</script>
