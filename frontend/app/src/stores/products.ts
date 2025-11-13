import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import type { Product } from "@/api/products/type";

export const useProductsStore = defineStore('products', () => {
  const products = ref<Product[]>([]);
  const isLoading = ref<boolean>(false);

  function setProducts(newProducts: Product[]) {
    products.value = JSON.parse(JSON.stringify(newProducts));
  }

  function addProduct(product: Product) {
    products.value.push(JSON.parse(JSON.stringify(product)));
  }

  function updateProduct(id: number, updatedProduct: Product) {
    const index = products.value.findIndex(p => p.id === id);
    if (index !== -1) {
      products.value[index] = JSON.parse(JSON.stringify(updatedProduct));
    }
  }

  function removeProduct(id: number) {
    products.value = products.value.filter(p => p.id !== id);
  }

  function setLoading(loading: boolean) {
    isLoading.value = loading;
  }

  const availableProducts = computed(() =>
    products.value.filter(p => p.status === 'available')
  );

  return {
    products,
    isLoading,
    availableProducts,
    setProducts,
    addProduct,
    updateProduct,
    removeProduct,
    setLoading
  }
})
