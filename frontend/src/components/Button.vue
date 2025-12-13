<script setup lang="ts">
defineProps<{
    type?: "button" | "submit" | "reset";
    color?: "blue" | "green" | "red";
    extraClass?: string;
}>();

const colors = {
    blue: "bg-blue-500 hover:bg-blue-600 text-white",
    green: "bg-green-500 hover:bg-green-600 text-white",
    red: "bg-red-500 hover:bg-red-600 text-white",
};
</script>

<template>
    <button
        :type="type || 'button'"
        :class="[
            'py-2 px-4 rounded font-semibold transition',
            colors[color || 'blue'],
            extraClass,
        ]"
    >
        <slot />
    </button>
</template>
