<template>
  <div class="absolute inset-0 bg-black bg-opacity-50 z-40">
    <div class="flex items-start justify-center max-h-screen mt-24 text-center">
      <div
        class="bg-white text-black rounded-lg shadow-xl p-6 w-1/5"
        role="dialog"
        aria-modal="true"
      >
        <div class="flex justify-center w-full">Are you sure to delete this?</div>
        <div class="flex justify-center py-4">
          <el-button type="primary" plain @click="$emit('confirm', true)">Yes</el-button>
          <el-button type="info" plain @click="$emit('confirm', false)">No</el-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
};
</script>
