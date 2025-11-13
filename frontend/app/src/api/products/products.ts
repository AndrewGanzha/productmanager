import axios from "@/plugins/axios.ts";
import type { Product, CreateProductData, UpdateProductData } from "@/api/products/type";

export async function getAllProducts(): Promise<Product[]> {
  const response = await axios.get<Product[]>('/products');
  return response.data;
}

export async function getAvailableProducts(): Promise<Product[]> {
  const response = await axios.get<Product[]>('/products/available');
  return response.data;
}

export async function getProduct(id: number): Promise<Product> {
  const response = await axios.get<Product>(`/products/${id}`);
  return response.data;
}

export async function createProduct(data: CreateProductData): Promise<{ message: string; product: Product }> {
  const response = await axios.post<{ message: string; product: Product }>('/products', data);
  return response.data;
}

export async function updateProduct(id: number, data: UpdateProductData): Promise<{ message: string; product: Product }> {
  const response = await axios.put<{ message: string; product: Product }>(`/products/${id}`, data);
  return response.data;
}

export async function deleteProduct(id: number): Promise<{ message: string }> {
  const response = await axios.delete<{ message: string }>(`/products/${id}`);
  return response.data;
}
