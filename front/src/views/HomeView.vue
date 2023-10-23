<script setup>
import TheWelcome from '../components/TheWelcome.vue'
</script>

<template>
  <main>
    <TheWelcome />
  </main>
</template>

<script>
import { useAuthStore } from '../stores/auth';
export default {
  mounted() {
    let auth  = useAuthStore();
    // console.log(auth.isLogedIn);
  }
}
</script>