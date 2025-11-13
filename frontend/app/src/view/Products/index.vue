<template>
  <div class="min-h-screen p-2 sm:p-3 md:p-4 lg:p-6 xl:p-8 pt-10 sm:pt-12 md:pt-14 lg:pt-16 xl:pt-20">
    <div class="max-w-7xl mx-auto">
      <div class="mb-3 sm:mb-4 md:mb-6 lg:mb-8">
        <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-white/95 mb-1 sm:mb-2">Управление продуктами</h1>
        <p class="text-[10px] sm:text-xs md:text-sm lg:text-base text-white/50">Создавайте, редактируйте и удаляйте продукты</p>
      </div>

      <ProductList
        :products="products"
        :available-products="availableProducts"
        :is-loading="isLoading"
        @create="openCreateModal"
        @edit="openEditModal"
        @delete="openDeleteModal"
      />

      <ProductForm
        v-if="showForm"
        :product="selectedProduct"
        :is-loading="isLoading"
        @submit="handleFormSubmit"
        @close="closeModal"
      />

      <div
        v-if="showDeleteConfirm"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-2 sm:p-4"
      >
        <div class="bg-slate-800/95 backdrop-blur-md border border-white/10 rounded-xl max-w-md w-full shadow-2xl">
          <div class="p-4 sm:p-6">
            <div class="flex items-start sm:items-center gap-3 sm:gap-4 mb-4">
              <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-red-500/20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="min-w-0 flex-1">
                <h3 class="text-base sm:text-lg font-semibold text-white/95">Удалить продукт?</h3>
                <p class="text-xs sm:text-sm text-white/50 mt-1 break-words">
                  {{ selectedProduct?.name }}
                </p>
              </div>
            </div>
            <p class="text-xs sm:text-sm text-white/70 mb-4 sm:mb-6">
              Это действие нельзя отменить. Продукт будет удален из системы.
            </p>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
              <button
                @click="closeModal"
                :disabled="isLoading"
                class="flex-1 px-4 py-2.5 bg-slate-700/50 text-white/70 border border-white/10 rounded-lg hover:bg-slate-700/70 transition-colors disabled:opacity-50"
              >
                Отмена
              </button>
              <button
                @click="handleDelete"
                :disabled="isLoading"
                class="flex-1 px-4 py-2.5 bg-red-500/20 text-red-300 border border-red-500/30 rounded-lg hover:bg-red-500/30 transition-colors disabled:opacity-50"
              >
                {{ isLoading ? 'Удаление...' : 'Удалить' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="notification"
        class="fixed top-2 right-2 sm:top-4 sm:right-4 z-[1001] animate-fade-in max-w-[calc(100vw-1rem)] sm:max-w-md"
      >
        <div
          :class="[
            'px-4 sm:px-6 py-3 sm:py-4 rounded-lg shadow-xl border backdrop-blur-md',
            notification.type === 'success'
              ? 'bg-green-500/20 text-green-300 border-green-500/30'
              : 'bg-red-500/20 text-red-300 border-red-500/30'
          ]"
        >
          <div class="flex items-center gap-2 sm:gap-3">
            <svg v-if="notification.type === 'success'" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <svg v-else class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium text-sm sm:text-base break-words">{{ notification.message }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue';
import ProductList from '@/components/Products/ProductList.vue';
import ProductForm from '@/components/Products/ProductForm.vue';
import { useProducts } from '@/composables/useProducts';
import type { Product } from '@/api/products/type';

const {
  products,
  availableProducts,
  isLoading,
  fetchProducts,
  createProduct,
  updateProduct,
  deleteProduct
} = useProducts();

const showForm = ref(false);
const showDeleteConfirm = ref(false);
const selectedProduct = ref<Product | undefined>(undefined);
const notification = ref<{ type: 'success' | 'error'; message: string } | null>(null);

const openCreateModal = () => {
  selectedProduct.value = undefined;
  showForm.value = true;
};

const openEditModal = (product: Product) => {
  selectedProduct.value = product;
  showForm.value = true;
};

const openDeleteModal = (product: Product) => {
  selectedProduct.value = product;
  showDeleteConfirm.value = true;
};

const closeModal = () => {
  showForm.value = false;
  showDeleteConfirm.value = false;
  selectedProduct.value = undefined;
};

const showNotification = (type: 'success' | 'error', message: string) => {
  notification.value = { type, message };
  setTimeout(() => {
    notification.value = null;
  }, 3000);
};

const handleFormSubmit = async (data: any) => {
  try {
    if (selectedProduct.value) {
      await updateProduct(selectedProduct.value.id, data);
      showNotification('success', 'Продукт успешно обновлен');
    } else {
      await createProduct(data);
      showNotification('success', 'Продукт успешно создан');
    }
    closeModal();
  } catch (err: any) {
    const errorMessage = err.response?.data?.errors
      ? Object.values(err.response.data.errors).flat().join(', ')
      : err.response?.data?.message || 'Произошла ошибка';
    showNotification('error', errorMessage);
  }
};

const handleDelete = async () => {
  if (!selectedProduct.value) return;

  try {
    await deleteProduct(selectedProduct.value.id);
    showNotification('success', 'Продукт успешно удален');
    closeModal();
  } catch (err: any) {
    const errorMessage = err.response?.data?.message || 'Ошибка при удалении продукта';
    showNotification('error', errorMessage);
    closeModal();
  }
};

onMounted(async () => {
  try {
    await fetchProducts();
  } catch (err) {
    showNotification('error', 'Не удалось загрузить продукты');
  }
});
</script>
