<script setup lang="ts"></script>
<template>
    <div
        class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-linear-to-br from-black via-[#2b2b2f] to-black"
    >
        <!-- LEFT: Illustration -->
        <div
            class="hidden lg:flex items-center justify-center relative overflow-hidden"
        >
            <img
                src="../assets/auth.png"
                alt="Auth Illustration"
                class="w-[85%] max-w-xl"
            />
        </div>

        <!-- RIGHT: Auth Content -->
        <div class="flex items-center justify-center px-6">
            <slot />
        </div>
    </div>
</template>
