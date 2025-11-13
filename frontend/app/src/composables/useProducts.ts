import { computed } from 'vue';
import { useProductsStore } from '@/stores/products';
import {
  getAllProducts as apiGetAllProducts,
  getAvailableProducts as apiGetAvailableProducts,
  createProduct as apiCreateProduct,
  updateProduct as apiUpdateProduct,
  deleteProduct as apiDeleteProduct
} from '@/api/products/products';
import type { CreateProductData, UpdateProductData } from "@/api/products/type";

export function useProducts() {
  const productsStore = useProductsStore();

  const products = computed(() => productsStore.products);
  const availableProducts = computed(() => productsStore.availableProducts);
  const isLoading = computed(() => productsStore.isLoading);

  const fetchProducts = async (): Promise<void> => {
    productsStore.setLoading(true);
    try {
      const data = await apiGetAllProducts();
      productsStore.setProducts(data);
    } catch (error) {
      console.error('Failed to fetch products:', error);
      throw error;
    } finally {
      productsStore.setLoading(false);
    }
  };

  const fetchAvailableProducts = async (): Promise<void> => {
    productsStore.setLoading(true);
    try {
      const data = await apiGetAvailableProducts();
      productsStore.setProducts(data);
    } catch (error) {
      console.error('Failed to fetch available products:', error);
      throw error;
    } finally {
      productsStore.setLoading(false);
    }
  };

  const createProduct = async (data: CreateProductData): Promise<void> => {
    productsStore.setLoading(true);
    try {
      const response = await apiCreateProduct(data);
      productsStore.addProduct(response.product);
    } catch (error) {
      console.error('Failed to create product:', error);
      throw error;
    } finally {
      productsStore.setLoading(false);
    }
  };

  const updateProduct = async (id: number, data: UpdateProductData): Promise<void> => {
    productsStore.setLoading(true);
    try {
      const response = await apiUpdateProduct(id, data);
      productsStore.updateProduct(id, response.product);
    } catch (error) {
      console.error('Failed to update product:', error);
      throw error;
    } finally {
      productsStore.setLoading(false);
    }
  };

  const deleteProduct = async (id: number): Promise<void> => {
    productsStore.setLoading(true);
    try {
      await apiDeleteProduct(id);
      productsStore.removeProduct(id);
    } catch (error) {
      console.error('Failed to delete product:', error);
      throw error;
    } finally {
      productsStore.setLoading(false);
    }
  };

  return {
    products,
    availableProducts,
    isLoading,
    fetchProducts,
    fetchAvailableProducts,
    createProduct,
    updateProduct,
    deleteProduct
  };
}
