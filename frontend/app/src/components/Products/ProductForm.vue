<template>
  <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-1.5 sm:p-2 md:p-3 lg:p-4">
    <div class="bg-slate-800/95 backdrop-blur-md border border-white/10 rounded-md sm:rounded-lg md:rounded-xl max-w-2xl w-full max-h-[97vh] sm:max-h-[96vh] md:max-h-[95vh] lg:max-h-[90vh] overflow-y-auto shadow-2xl">
      <div class="sticky top-0 bg-slate-800/95 backdrop-blur-md border-b border-white/10 p-2 sm:p-3 md:p-4 lg:p-6 flex justify-between items-center">
        <h2 class="text-sm sm:text-base md:text-lg lg:text-xl font-semibold text-white/95">
          {{ isEditMode ? 'Редактировать продукт' : 'Создать продукт' }}
        </h2>
        <button
          @click="$emit('close')"
          class="text-white/50 hover:text-white/90 transition-colors flex-shrink-0 ml-1.5 sm:ml-2"
        >
          <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 md:w-5 md:h-5 lg:w-6 lg:h-6" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>

      <div class="p-2 sm:p-3 md:p-4 lg:p-6">
        <div v-if="error" class="mb-2 sm:mb-3 md:mb-4 p-2 sm:p-2.5 md:p-3 rounded-lg text-[10px] sm:text-xs md:text-sm bg-red-500/10 text-red-300 border border-red-500/30">
          {{ error }}
        </div>

        <div class="space-y-2 sm:space-y-3 md:space-y-4">
          <div v-if="!isEditMode || (isEditMode && userRole === 'admin')">
            <label class="block text-[10px] sm:text-xs md:text-sm font-medium text-white/70 mb-1 sm:mb-1.5 md:mb-2">
              Артикул <span class="text-red-400">*</span>
            </label>
            <input
              v-model="formData.article"
              type="text"
              placeholder="ABC123"
              :disabled="isLoading"
              class="w-full px-2.5 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base bg-slate-700/50 border border-white/10 rounded-lg text-white/90 placeholder-white/30 focus:outline-none focus:border-blue-400/50 transition-colors disabled:opacity-50"
            />
          </div>

          <div>
            <label class="block text-[10px] sm:text-xs md:text-sm font-medium text-white/70 mb-1 sm:mb-1.5 md:mb-2">
              Название <span class="text-red-400">*</span>
            </label>
            <input
              v-model="formData.name"
              type="text"
              placeholder="Минимум 10 символов"
              :disabled="isLoading"
              class="w-full px-2.5 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base bg-slate-700/50 border border-white/10 rounded-lg text-white/90 placeholder-white/30 focus:outline-none focus:border-blue-400/50 transition-colors disabled:opacity-50"
            />
          </div>

          <div>
            <label class="block text-[10px] sm:text-xs md:text-sm font-medium text-white/70 mb-1 sm:mb-1.5 md:mb-2">Статус</label>
            <select
              v-model="formData.status"
              :disabled="isLoading"
              class="w-full px-2.5 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base bg-slate-700/50 border border-white/10 rounded-lg text-white/90 focus:outline-none focus:border-blue-400/50 transition-colors disabled:opacity-50"
            >
              <option value="available">Доступен</option>
              <option value="unavailable">Недоступен</option>
            </select>
          </div>

          <div>
            <label class="block text-[10px] sm:text-xs md:text-sm font-medium text-white/70 mb-1 sm:mb-1.5 md:mb-2">Дополнительные данные (JSON)</label>
            <div class="space-y-1 sm:space-y-1.5 md:space-y-2">
              <div
                v-for="(item, index) in dataFields"
                :key="index"
                class="flex flex-col sm:flex-row gap-1 sm:gap-1.5 md:gap-2"
              >
                <input
                  v-model="item.key"
                  type="text"
                  placeholder="Ключ (Color, Size...)"
                  :disabled="isLoading"
                  class="flex-1 px-2 sm:px-2.5 md:px-3 lg:px-4 py-1 sm:py-1.5 md:py-2 lg:py-2.5 text-[10px] sm:text-xs md:text-sm lg:text-base bg-slate-700/50 border border-white/10 rounded-lg text-white/90 placeholder-white/30 focus:outline-none focus:border-blue-400/50 transition-colors disabled:opacity-50"
                />
                <div class="flex gap-1 sm:gap-1.5 md:gap-2">
                  <input
                    v-model="item.value"
                    type="text"
                    placeholder="Значение"
                    :disabled="isLoading"
                    class="flex-1 px-2 sm:px-2.5 md:px-3 lg:px-4 py-1 sm:py-1.5 md:py-2 lg:py-2.5 text-[10px] sm:text-xs md:text-sm lg:text-base bg-slate-700/50 border border-white/10 rounded-lg text-white/90 placeholder-white/30 focus:outline-none focus:border-blue-400/50 transition-colors disabled:opacity-50"
                  />
                  <button
                    @click="removeDataField(index)"
                    :disabled="isLoading"
                    class="px-1.5 sm:px-2 md:px-2.5 lg:px-3 py-1 sm:py-1.5 md:py-2 lg:py-2.5 bg-red-500/10 text-red-300 border border-red-500/30 rounded-lg hover:bg-red-500/20 transition-colors disabled:opacity-50 flex-shrink-0"
                  >
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 lg:w-5 lg:h-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
              </div>
              <button
                @click="addDataField"
                :disabled="isLoading"
                class="w-full px-2.5 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-[10px] sm:text-xs md:text-sm bg-blue-500/10 text-blue-300 border border-blue-500/30 rounded-lg hover:bg-blue-500/20 transition-colors disabled:opacity-50"
              >
                + Добавить поле
              </button>
            </div>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-1.5 sm:gap-2 mt-2 sm:mt-3 md:mt-4 lg:mt-6">
          <button
            @click="$emit('close')"
            :disabled="isLoading"
            class="flex-1 px-2.5 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-[10px] sm:text-xs md:text-sm lg:text-base bg-slate-700/50 text-white/70 border border-white/10 rounded-lg hover:bg-slate-700/70 transition-colors disabled:opacity-50"
          >
            Отмена
          </button>
          <button
            @click="handleSubmit"
            :disabled="isLoading || !isFormValid"
            class="flex-1 px-2.5 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-[10px] sm:text-xs md:text-sm lg:text-base bg-blue-500/20 text-blue-300 border border-blue-500/30 rounded-lg hover:bg-blue-500/30 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ isLoading ? 'Сохранение...' : 'Сохранить' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted } from 'vue';
import type { Product } from '@/api/products/type';
import { useAuth } from '@/composables/useAuth';

const props = defineProps<{
  product?: Product;
  isLoading?: boolean;
}>();

const emit = defineEmits<{
  submit: [data: any];
  close: [];
}>();

const { user } = useAuth();

const userRole = computed(() => user.value?.role || 'user');
const isEditMode = computed(() => !!props.product);

const formData = ref({
  article: '',
  name: '',
  status: 'available' as 'available' | 'unavailable',
});

const dataFields = ref<{ key: string; value: string }[]>([]);
const error = ref('');

const isFormValid = computed(() => {
  if (isEditMode.value && userRole.value !== 'admin') {
    return formData.value.name.length >= 10;
  }
  return formData.value.article && formData.value.name.length >= 10;
});

const addDataField = () => {
  dataFields.value.push({ key: '', value: '' });
};

const removeDataField = (index: number) => {
  dataFields.value.splice(index, 1);
};

const handleSubmit = async () => {
  error.value = '';

  if (!formData.value.article && !isEditMode.value) {
    error.value = 'Артикул обязателен для заполнения';
    return;
  }

  if (formData.value.name.length < 10) {
    error.value = 'Название должно содержать минимум 10 символов';
    return;
  }

  const data: any = {
    name: formData.value.name,
    status: formData.value.status,
  };

  if (!isEditMode.value || userRole.value === 'admin') {
    data.article = formData.value.article;
  }

  const filteredDataFields = dataFields.value.filter(f => f.key && f.value);
  if (filteredDataFields.length > 0) {
    data.data = Object.fromEntries(
      filteredDataFields.map(f => [f.key, f.value])
    );
  }

  emit('submit', data);
};

onMounted(() => {
  if (props.product) {
    formData.value.article = props.product.article;
    formData.value.name = props.product.name;
    formData.value.status = props.product.status;

    if (props.product.data) {
      dataFields.value = Object.entries(props.product.data).map(([key, value]) => ({
        key,
        value: String(value)
      }));
    }
  }
});
</script>
