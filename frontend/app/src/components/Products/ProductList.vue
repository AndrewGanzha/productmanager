<template>
  <div class="w-full">
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
      <div class="flex gap-2">
        <button
          @click="showAll = true"
          :class="[
            'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
            showAll
              ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30'
              : 'bg-slate-700/50 text-white/50 border border-white/10 hover:text-white/70'
          ]"
        >
          Все продукты
        </button>
        <button
          @click="showAll = false"
          :class="[
            'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200',
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
        class="flex items-center gap-2 px-4 py-2 bg-green-500/20 text-green-300 border border-green-500/30 rounded-lg hover:bg-green-500/30 transition-colors"
      >
        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        Создать продукт
      </button>
    </div>

    <div v-if="isLoading" class="flex justify-center items-center py-20">
      <div class="w-12 h-12 border-4 border-white/10 border-t-blue-400 rounded-full animate-spin"></div>
    </div>

    <div v-else-if="displayedProducts.length === 0" class="text-center py-20">
      <svg class="w-16 h-16 mx-auto text-white/20 mb-4" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd" />
      </svg>
      <p class="text-white/50 text-lg">Продукты не найдены</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
