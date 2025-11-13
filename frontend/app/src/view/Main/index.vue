<template>
  <div class="min-h-screen">
    <div class="flex items-center justify-center min-h-screen p-2 sm:p-3 md:p-4 lg:p-6 xl:p-8 pt-10 sm:pt-12 md:pt-14 lg:pt-16 xl:pt-20">
      <div class="text-center max-w-md w-full">
        <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-white/95 mb-3 sm:mb-4">Добро пожаловать!</h1>
        <p class="text-[10px] sm:text-xs md:text-sm lg:text-base text-white/50 mb-5 sm:mb-6 md:mb-8">Выберите раздел для работы</p>
        <router-link
          to="/products"
          class="inline-flex items-center justify-center gap-1.5 sm:gap-2 w-full sm:w-auto px-3 sm:px-4 md:px-5 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-blue-500/20 text-blue-300 border border-blue-500/30 rounded-lg hover:bg-blue-500/30 transition-colors text-[10px] sm:text-xs md:text-sm lg:text-base"
        >
          <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-5 lg:h-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
          </svg>
          <span>Управление продуктами</span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
</script>
