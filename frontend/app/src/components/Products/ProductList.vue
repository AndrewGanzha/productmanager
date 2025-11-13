<template>
  <div class="w-full">
    <div class="mb-2 sm:mb-3 md:mb-4 lg:mb-6 flex flex-col gap-1.5 sm:gap-2 md:gap-3 lg:gap-4 sm:flex-row sm:justify-between sm:items-center">
      <div class="flex gap-1 sm:gap-1.5 md:gap-2 w-full sm:w-auto">
        <button
          @click="showAll = true"
          :class="[
            'flex-1 sm:flex-none px-2 sm:px-2.5 md:px-3 lg:px-4 py-1 sm:py-1.5 md:py-2 rounded-lg text-[10px] sm:text-xs md:text-sm font-medium transition-all duration-200',
            showAll
              ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30'
              : 'bg-slate-700/50 text-white/50 border border-white/10 hover:text-white/70'
          ]"
        >
          Все
        </button>
        <button
          @click="showAll = false"
          :class="[
            'flex-1 sm:flex-none px-2 sm:px-2.5 md:px-3 lg:px-4 py-1 sm:py-1.5 md:py-2 rounded-lg text-[10px] sm:text-xs md:text-sm font-medium transition-all duration-200',
            !showAll
              ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30'
              : 'bg-slate-700/50 text-white/50 border border-white/10 hover:text-white/70'
          ]"
        >
          Доступные
        </button>
      </div>

      <button
        @click="$emit('create')"
        class="w-full sm:w-auto flex items-center justify-center gap-1 sm:gap-1.5 md:gap-2 px-2.5 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 lg:py-2 bg-green-500/20 text-green-300 border border-green-500/30 rounded-lg hover:bg-green-500/30 transition-colors text-[10px] sm:text-xs md:text-sm font-medium"
      >
        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-5 lg:h-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        <span>Создать продукт</span>
      </button>
    </div>

    <div v-if="isLoading" class="flex justify-center items-center py-10 sm:py-12 md:py-16 lg:py-20">
      <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 border-4 border-white/10 border-t-blue-400 rounded-full animate-spin"></div>
    </div>

    <div v-else-if="displayedProducts.length === 0" class="text-center py-10 sm:py-12 md:py-16 lg:py-20">
      <svg class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 lg:w-16 lg:h-16 mx-auto text-white/20 mb-2 sm:mb-3 md:mb-4" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd" />
      </svg>
      <p class="text-white/50 text-xs sm:text-sm md:text-base lg:text-lg">Продукты не найдены</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3 md:gap-4">
      <ProductCard
        v-for="product in displayedProducts"
        :key="product.id"
        :product="product"
        @edit="$emit('edit', $event)"
        @delete="$emit('delete', $event)"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import ProductCard from './ProductCard.vue';
import type { Product } from '@/api/products/type';

const props = defineProps<{
  products: Product[];
  availableProducts: Product[];
  isLoading: boolean;
}>();

defineEmits<{
  create: [];
  edit: [product: Product];
  delete: [product: Product];
}>();

const showAll = ref(true);

const displayedProducts = computed(() => {
  return showAll.value ? props.products : props.availableProducts;
});
</script>
