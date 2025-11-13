<template>
  <div class="flex items-center justify-center h-screen px-4">
    <div class="max-w-md w-full">
      <div class="flex gap-2 border-b-2 border-white/10 mb-4">
        <button
          @click="mode = 'login'"
          :class="[
            'flex-1 px-4 py-3 bg-transparent border-0 border-b-2 cursor-pointer text-base font-medium transition-all duration-200 -mb-0.5',
            mode === 'login'
              ? 'text-white/95 border-b-blue-400'
              : 'text-white/50 border-transparent hover:text-white/75',
          ]"
        >
          Вход
        </button>
        <button
          @click="mode = 'register'"
          :class="[
            'flex-1 px-4 py-3 bg-transparent border-0 border-b-2 cursor-pointer text-base font-medium transition-all duration-200 -mb-0.5',
            mode === 'register'
              ? 'text-white/95 border-b-blue-400'
              : 'text-white/50 border-transparent hover:text-white/75',
          ]"
        >
          Регистрация
        </button>
      </div>

      <fieldset
        class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4 mx-auto my-0"
      >
        <legend class="fieldset-legend">
          {{ mode === "login" ? "Вход в аккаунт" : "Создание аккаунта" }}
        </legend>

        <div
          v-if="error"
          class="p-3 rounded-lg text-sm bg-red-500/10 text-red-300 border border-red-500/30 mb-4"
        >
          {{ error }}
        </div>

        <!-- Поле Имя (только для регистрации) -->
        <div v-if="mode === 'register'">
          <label class="label">Имя</label>
          <input
            type="text"
            class="input"
            placeholder="Иван Иванов"
            v-model="name"
            :disabled="isLoading"
          />
        </div>

        <!-- Email -->
        <label class="label">Email</label>
        <input
          type="email"
          class="input"
          placeholder="user@example.com"
          v-model="email"
          :disabled="isLoading"
          @keyup.enter="handleSubmit"
        />

        <!-- Пароль -->
        <label class="label">Пароль</label>
        <input
          type="password"
          class="input"
          :placeholder="mode === 'register' ? 'Минимум 8 символов' : 'Введите пароль'"
          v-model="password"
          :disabled="isLoading"
          @keyup.enter="handleSubmit"
        />

        <!-- Подтверждение пароля (только для регистрации) -->
        <div v-if="mode === 'register'">
          <label class="label">Подтверждение пароля</label>
          <input
            type="password"
            class="input"
            placeholder="Повторите пароль"
            v-model="passwordConfirmation"
            :disabled="isLoading"
            @keyup.enter="handleSubmit"
          />
        </div>

        <!-- Кнопка отправки -->
        <button
          class="btn btn-neutral mt-4 w-full"
          @click="handleSubmit"
          :disabled="isLoading || !isFormValid"
        >
          {{ submitButtonText }}
        </button>
      </fieldset>

      <!-- Дополнительная подсказка -->
      <div class="text-center mt-4 text-sm text-white/70">
        <span v-if="mode === 'login'">
          Нет аккаунта?
          <button
            @click="mode = 'register'"
            class="bg-transparent border-0 text-blue-400 cursor-pointer underline p-0 text-sm hover:text-blue-300 transition-colors"
          >
            Зарегистрируйтесь
          </button>
        </span>
        <span v-else>
          Уже есть аккаунт?
          <button
            @click="mode = 'login'"
            class="bg-transparent border-0 text-blue-400 cursor-pointer underline p-0 text-sm hover:text-blue-300 transition-colors"
          >
            Войдите
          </button>
        </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { useAuth } from "@/composables/useAuth";

const { login, register, isLoading } = useAuth();

type AuthMode = "login" | "register";

const mode = ref<AuthMode>("login");
const name = ref("");
const email = ref("");
const password = ref("");
const passwordConfirmation = ref("");
const error = ref("");

const isFormValid = computed(() => {
  if (mode.value === "login") {
    return email.value && password.value;
  } else {
    return name.value && email.value && password.value && passwordConfirmation.value;
  }
});

const submitButtonText = computed(() => {
  if (isLoading.value) {
    return mode.value === "login" ? "Вход..." : "Регистрация...";
  }
  return mode.value === "login" ? "Войти" : "Зарегистрироваться";
});

const handleSubmit = async () => {
  error.value = "";

  if (mode.value === "login") {
    await handleLogin();
  } else {
    await handleRegister();
  }
};

const handleLogin = async () => {
  if (!email.value || !password.value) {
    error.value = "Заполните все поля";
    return;
  }

  try {
    await login(email.value, password.value);
  } catch (err: any) {
    error.value = err.response?.data?.message || "Ошибка входа. Проверьте email и пароль.";
  }
};

const handleRegister = async () => {
  if (!name.value || !email.value || !password.value || !passwordConfirmation.value) {
    error.value = "Заполните все поля";
    return;
  }

  if (password.value.length < 8) {
    error.value = "Пароль должен содержать минимум 8 символов";
    return;
  }

  if (password.value !== passwordConfirmation.value) {
    error.value = "Пароли не совпадают";
    return;
  }

  try {
    await register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
    });
  } catch (err: any) {
    if (err.response?.data?.errors) {
      const errors = err.response.data.errors;
      error.value = Object.values(errors).flat().join(", ");
    } else {
      error.value = err.response?.data?.message || "Ошибка регистрации. Попробуйте снова.";
    }
  }
};

const resetForm = () => {
  error.value = "";
};

import { watch } from "vue";
watch(mode, () => {
  resetForm();
});
</script>
