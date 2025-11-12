<template>
  <div class="fixed top-4 left-4 z-[1000]">
    <div
      v-if="user"
      class="relative bg-slate-800/80 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 cursor-pointer transition-all duration-200 shadow-lg hover:bg-slate-800/90 hover:border-white/20 hover:shadow-xl"
      @click="toggleDropdown"
    >
      <!-- Компактный вид -->
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-base font-semibold text-white flex-shrink-0 shadow-lg shadow-indigo-500/40">
          {{ user.name.charAt(0).toUpperCase() }}
        </div>
        <div class="flex flex-col gap-0.5 min-w-0">
          <span class="text-sm font-semibold text-white/95 whitespace-nowrap overflow-hidden text-ellipsis max-w-[120px]">
            {{ user.name }}
          </span>
          <span
            class="inline-block px-2 py-0.5 rounded text-xs font-medium w-fit"
            :class="user.role === 'admin'
              ? 'bg-red-500/20 text-red-300 border border-red-500/30'
              : 'bg-blue-500/20 text-blue-300 border border-blue-500/30'"
          >
            {{ getRoleLabel(user.role) }}
          </span>
        </div>
        <svg
          class="w-5 h-5 text-white/60 transition-transform duration-200 flex-shrink-0"
          :class="{ 'rotate-180': isDropdownOpen }"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </div>

      <!-- Выпадающее меню -->
      <transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        leave-active-class="transition duration-200 ease-in"
        leave-to-class="opacity-0 -translate-y-2"
      >
        <div
          v-if="isDropdownOpen"
          class="absolute top-[calc(100%+0.5rem)] left-0 min-w-[250px] bg-slate-800/95 backdrop-blur-md border border-white/10 rounded-lg shadow-2xl overflow-hidden"
        >
          <div class="p-3">
            <div class="flex justify-between py-2 border-b border-white/5">
              <span class="text-xs text-white/50 font-medium">Email:</span>
              <span class="text-xs text-white/90 font-medium text-right overflow-hidden text-ellipsis whitespace-nowrap max-w-[150px]">
                {{ user.email }}
              </span>
            </div>
            <div class="flex justify-between py-2">
              <span class="text-xs text-white/50 font-medium">ID:</span>
              <span class="text-xs text-white/90 font-medium text-right overflow-hidden text-ellipsis whitespace-nowrap max-w-[150px]">
                {{ user.id }}
              </span>
            </div>
          </div>

          <div class="h-px bg-white/10"></div>

          <button
            @click.stop="handleLogout"
            :disabled="isLoading"
            class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-red-500/10 text-red-300 border-t border-red-500/20 text-sm font-medium transition-colors duration-200 hover:bg-red-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg class="w-4 h-4" viewBox="0 0 16 16" fill="currentColor">
              <path fill-rule="evenodd" d="M3 3.5A1.5 1.5 0 014.5 2h6A1.5 1.5 0 0112 3.5v2a.5.5 0 01-1 0v-2a.5.5 0 00-.5-.5h-6a.5.5 0 00-.5.5v9a.5.5 0 00.5.5h6a.5.5 0 00.5-.5v-2a.5.5 0 011 0v2A1.5 1.5 0 0110.5 14h-6A1.5 1.5 0 013 12.5v-9z"/>
              <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 000-.708l-3-3a.5.5 0 10-.708.708L10.293 7.5H5.5a.5.5 0 000 1h4.793l-2.147 2.146a.5.5 0 00.708.708l3-3z"/>
            </svg>
            <span v-if="!isLoading">Выйти</span>
            <span v-else>Выход...</span>
          </button>
        </div>
      </transition>
    </div>

    <!-- Loading состояние -->
    <div
      v-else
      class="bg-slate-800/80 backdrop-blur-md border border-white/10 rounded-xl px-3 py-2 flex items-center justify-center"
    >
      <div class="w-8 h-8 border-2 border-white/10 border-t-blue-400 rounded-full animate-spin"></div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import { useAuth } from "@/composables/useAuth";

const { user, isLoading, logout } = useAuth();

const isDropdownOpen = ref(false);

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const handleLogout = async () => {
  isDropdownOpen.value = false;
  await logout();
};

const getRoleLabel = (role: string): string => {
  const labels: Record<string, string> = {
    'admin': 'Администратор',
    'user': 'Пользователь'
  };
  return labels[role] || role;
};
</script>
