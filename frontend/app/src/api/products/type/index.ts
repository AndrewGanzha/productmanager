export interface Product {
  id: number;
  article: string;
  name: string;
  status: 'available' | 'unavailable';
  data: Record<string, any> | null;
  created_at: string;
  updated_at: string;
  deleted_at?: string | null;
}

export interface CreateProductData {
  article: string;
  name: string;
  status?: 'available' | 'unavailable';
  data?: Record<string, any>;
}

export interface UpdateProductData {
  article?: string;
  name?: string;
  status?: 'available' | 'unavailable';
  data?: Record<string, any>;
}
