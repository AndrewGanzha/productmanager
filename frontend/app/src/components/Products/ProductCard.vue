<template>
  <div class="bg-slate-800/80 backdrop-blur-md border border-white/10 rounded-md sm:rounded-lg md:rounded-xl p-2 sm:p-3 md:p-4 lg:p-5 hover:border-white/20 transition-all duration-200 shadow-lg hover:shadow-xl">
    <div class="flex flex-col sm:flex-row justify-between items-start gap-1.5 sm:gap-2 md:gap-0 mb-1.5 sm:mb-2 md:mb-3">
      <div class="flex-1 min-w-0 w-full sm:w-auto">
        <h3 class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-white/95 mb-0.5 sm:mb-1 break-words">{{ product.name }}</h3>
        <p class="text-[10px] sm:text-xs md:text-xs lg:text-sm text-white/50 font-mono">{{ product.article }}</p>
      </div>
      <span
        class="inline-block px-1.5 sm:px-2 md:px-2.5 lg:px-3 py-0.5 rounded text-[10px] sm:text-xs font-medium whitespace-nowrap"
        :class="statusClasses"
      >
        {{ statusLabel }}
      </span>
    </div>

    <div v-if="product.data && Object.keys(product.data).length > 0" class="mb-1.5 sm:mb-2 md:mb-3 lg:mb-4">
      <div class="flex flex-wrap gap-0.5 sm:gap-1 md:gap-1.5 lg:gap-2">
        <div
          v-for="(value, key) in product.data"
          :key="key"
          class="bg-slate-700/50 border border-white/5 rounded px-1 sm:px-1.5 md:px-2 lg:px-3 py-0.5 md:py-1 lg:py-1.5"
        >
          <span class="text-[9px] sm:text-[10px] md:text-xs text-white/50">{{ key }}:</span>
          <span class="text-[9px] sm:text-[10px] md:text-xs text-white/90 ml-0.5 font-medium">{{ value }}</span>
        </div>
      </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-1.5 sm:gap-2 mt-1.5 sm:mt-2 md:mt-3 lg:mt-4">
      <button
        @click="$emit('edit', product)"
        class="flex-1 flex items-center justify-center gap-1 sm:gap-1.5 md:gap-2 px-2 sm:px-2.5 md:px-3 py-1.5 sm:py-2 md:py-2.5 lg:py-2 bg-blue-500/10 text-blue-300 border border-blue-500/30 rounded-lg text-[10px] sm:text-xs md:text-sm font-medium transition-colors duration-200 hover:bg-blue-500/20"
      >
        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" viewBox="0 0 20 20" fill="currentColor">
          <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
        </svg>
        <span>Изменить</span>
      </button>
      <button
        @click="$emit('delete', product)"
        class="flex-1 flex items-center justify-center gap-1 sm:gap-1.5 md:gap-2 px-2 sm:px-2.5 md:px-3 py-1.5 sm:py-2 md:py-2.5 lg:py-2 bg-red-500/10 text-red-300 border border-red-500/30 rounded-lg text-[10px] sm:text-xs md:text-sm font-medium transition-colors duration-200 hover:bg-red-500/20"
      >
        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <span>Удалить</span>
      </button>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue';
import type { Product } from '@/api/products/type';

const props = defineProps<{
  product: Product;
}>();

defineEmits<{
  edit: [product: Product];
  delete: [product: Product];
}>();

const statusLabel = computed(() => {
  return props.product.status === 'available' ? 'Доступен' : 'Недоступен';
});

const statusClasses = computed(() => {
  return props.product.status === 'available'
    ? 'bg-green-500/20 text-green-300 border border-green-500/30'
    : 'bg-red-500/20 text-red-300 border border-red-500/30';
});
</script>
