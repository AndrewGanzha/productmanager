<template>
  <div v-if="user" class="flex flex-col gap-3 defaultBorder size-min p-2">
    <p>Email: {{ user.email }}</p>
    <p>Name: {{ user.name }}</p>
  </div>
</template>

<script lang="ts" setup>
import {useUserStore} from "@/stores/user.ts";
import {ref} from "vue";

const userStore = useUserStore();
const user = ref(userStore.user);
</script>
