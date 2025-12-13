<script setup lang="ts">
defineProps<{
    extraClass?: string;
}>();
</script>

<template>
    <div
        :class="[
            'w-full max-w-md bg-[#1f1f23] text-gray-200 rounded-xl shadow-xl p-8',
            extraClass,
        ]"
    >
        <slot />
    </div>
</template>
