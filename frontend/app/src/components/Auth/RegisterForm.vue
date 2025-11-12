<template>
  <div class="auth-form">
    <h2>Регистрация</h2>
    <form @submit.prevent="handleRegister">
      <div class="form-group">
        <label for="name">Имя:</label>
        <input
          id="name"
          v-model="formData.name"
          type="text"
          required
          placeholder="Иван Иванов"
        />
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input
          id="email"
          v-model="formData.email"
          type="email"
          required
          placeholder="user@example.com"
        />
      </div>

      <div class="form-group">
        <label for="password">Пароль:</label>
        <input
          id="password"
          v-model="formData.password"
          type="password"
          required
          minlength="8"
          placeholder="Минимум 8 символов"
        />
      </div>

      <div class="form-group">
        <label for="password_confirmation">Подтверждение пароля:</label>
        <input
          id="password_confirmation"
          v-model="formData.password_confirmation"
          type="password"
          required
          minlength="8"
          placeholder="Повторите пароль"
        />
      </div>

      <div v-if="error" class="error-message">
        {{ error }}
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Регистрация...' : 'Зарегистрироваться' }}
      </button>
    </form>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import { register } from '@/api/auth/auth';
import { useUserStore } from '@/stores/user';
import type { RegisterData } from '@/api/auth/auth';

const userStore = useUserStore();

const formData = ref<RegisterData>({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const loading = ref(false);
const error = ref('');

const handleRegister = async () => {
  if (formData.value.password !== formData.value.password_confirmation) {
    error.value = 'Пароли не совпадают';
    return;
  }

  loading.value = true;
  error.value = '';

  try {
    const user = await register(formData.value);
    userStore.setUser(user);
    // Можно добавить редирект
    // router.push('/dashboard');
  } catch (err: any) {
    if (err.response?.data?.errors) {
      const errors = err.response.data.errors;
      error.value = Object.values(errors).flat().join(', ');
    } else {
      error.value = err.response?.data?.message || 'Ошибка регистрации. Попробуйте снова.';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.auth-form {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
}

.form-group input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.error-message {
  color: red;
  margin-bottom: 10px;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

button:hover:not(:disabled) {
  background-color: #45a049;
}
</style>
