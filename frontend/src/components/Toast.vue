<script setup lang="ts">
import { useToastStore } from "@/stores/useToastStore.ts";

const toast = useToastStore();
</script>

<template>
    <transition name="fade">
        <div
            v-if="toast.visible"
            :class="[
                'fixed top-4 right-4 z-50 text-white p-3 rounded shadow',
                toast.type === 'success'
                    ? 'bg-green-500'
                    : toast.type === 'error'
                      ? 'bg-red-500'
                      : 'bg-blue-500',
            ]"
        >
            {{ toast.message }}
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
