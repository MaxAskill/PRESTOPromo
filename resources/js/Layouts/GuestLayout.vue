<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
  <div
    class="min-h-screen flex flex-col justify-center items-center pt-0 sm:px-0 2xs:px-6 px-3 bg-gray-100"
  >
    <div>
      <img src="../../../public/images/CompanyIcon.png" class="w-20 h-20" />
    </div>

    <div
      class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
    >
      <slot />
    </div>
  </div>
</template>
