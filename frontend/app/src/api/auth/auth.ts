import axios from "@/plugins/axios.ts";
import type {User} from "@/api/auth/type";

export async function login(email: string, password: string) {
  await axios.post('/auth/login', {
    email,
    password
  });
}

export async function getUser(): Promise<User | null> {
  try {
    const response = await axios.get<User>('/user');
    return response.data;
  } catch (e) {
    console.error(e);
    return null;
  }
}
