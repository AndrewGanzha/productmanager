import axios from "@/plugins/axios.ts";
import type {AuthResponse, RegisterData, User} from "@/api/auth/type";
import { tokenStorage } from "@/utils/tokenStorage";
export async function register(data: RegisterData): Promise<User> {
  const response = await axios.post<AuthResponse>('/auth/register', data);
  tokenStorage.setToken(response.data.token);
  return response.data.user;
}

export async function login(email: string, password: string): Promise<User> {
  const response = await axios.post<AuthResponse>('/auth/login', {
    email,
    password
  });
  tokenStorage.setToken(response.data.token);
  return response.data.user;
}

export async function logout(): Promise<void> {
  try {
    await axios.post('/auth/logout');
  } finally {
    tokenStorage.removeToken();
  }
}

export async function getUser(): Promise<User | null> {
  if (!tokenStorage.hasToken()) {
    return null;
  }

  try {
    const response = await axios.get<User>('/user');
    return response.data;
  } catch (e) {
    console.error(e);
    tokenStorage.removeToken();
    return null;
  }
}
