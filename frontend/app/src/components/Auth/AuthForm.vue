<template>
  <div class="flex items-center justify-center h-screen">
    <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
      <legend class="fieldset-legend">Login</legend>

      <label class="label">Email</label>
      <input type="email" class="input" placeholder="Email" v-model="email"/>

      <label class="label">Password</label>
      <input type="password" class="input" placeholder="Password" v-model="password"/>

      <button class="btn btn-neutral mt-4" @click="login(email, password)">Login</button>
    </fieldset>
  </div>
</template>

<script setup lang="ts">
import {ref} from "vue";
import {login} from "@/api/auth/auth.ts";

const email = ref('');
const password = ref('');
</script>
