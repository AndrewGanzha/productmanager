import axios from "@/plugins/axios.ts";

export async function login(email: string, password: string) {
  await axios.post('/auth/login', {
    email,
    password
  });
}
