import axiosLib from 'axios'
import { tokenStorage } from '@/utils/tokenStorage'

const axios = axiosLib.create({
  baseURL: import.meta.env.VITE_BACKEND_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
  },
})
axios.interceptors.request.use(
  (config) => {
    const token = tokenStorage.getToken()

    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }

    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)
axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      tokenStorage.removeToken()
    }

    return Promise.reject(error)
  }
)

export default axios
