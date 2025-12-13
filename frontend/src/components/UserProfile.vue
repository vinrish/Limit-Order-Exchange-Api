<script setup lang="ts">
import { useProfileStore } from "@/stores/useProfileStore.ts";

const profileStore = useProfileStore();

const profile = computed(() => profileStore.profileData);
const balances = computed(() => profileStore.balances);
</script>

<template>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- User Info Card -->
        <Card class="bg-linear-to-bl from-black via-[#2b2b2f] to-black card">
            <h2 class="text-xl font-bold mb-4 text-emerald-400">User Info</h2>
            <div class="mb-2">
                <strong>Name:</strong> {{ profile?.name || "-" }}
            </div>
            <div class="mb-2">
                <strong>Email:</strong> {{ profile?.email || "-" }}
            </div>
            <div class="mb-2">
                <strong>USD Balance:</strong> ${{
                    balances.usd?.toFixed(2) ?? 0
                }}
            </div>
        </Card>

        <!-- Assets Card -->
        <Card class="bg-linear-to-bl from-black via-[#2b2b2f] to-black card">
            <h2 class="text-xl font-bold mb-4 text-emerald-400">Assets</h2>
            <ul class="ml-4">
                <li
                    v-if="Object.keys(balances.assets).length === 0"
                    class="text-gray-400"
                >
                    <strong class="text-white">No assets</strong>
                </li>
                <li v-for="(amount, symbol) in balances.assets" :key="symbol">
                    <strong>{{ symbol }}: {{ amount.toFixed(8) }}</strong>
                </li>
            </ul>
        </Card>
    </div>
</template>

<style scoped>
.card {
    display: inline-block;
    animation: bounce 6s infinite ease-in-out;
}

/* Keyframes for bounce effect */
@keyframes bounce {
    0% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-10px);
    }
    50% {
        transform: translateY(0);
    }
    70% {
        transform: translateY(-5px);
    }
    100% {
        transform: translateY(0);
    }
}
</style>
