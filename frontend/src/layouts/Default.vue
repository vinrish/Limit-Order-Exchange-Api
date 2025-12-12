<script setup lang="ts">
// optional: you can pass a title or other props if needed
defineProps<{ title?: string }>();
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gray-800 text-white p-4">
            <h1>{{ title || "Limit Order Exchange" }}</h1>
        </header>

        <main class="flex-1 p-6">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white p-4 text-center">
            &copy; 2025 Limit Order Exchange
        </footer>
    </div>
</template>
