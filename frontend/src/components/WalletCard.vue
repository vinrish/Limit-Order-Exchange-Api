<script setup lang="ts">
import { useProfileStore } from "@/stores/useProfileStore";

const store = useProfileStore();
</script>

<template>
    <Card
        class="bg-linear-to-bl from-black via-[#2b2b2f] to-black card p-4 rounded"
    >
        <h3 class="font-semibold text-emerald-400 mb-2 text-2xl">Wallet</h3>

        <p>
            USD: <b>{{ store.balances.usd.toFixed(2) }}</b>
        </p>

        <div v-for="(v, k) in store.balances.assets" :key="k">
            {{ k }}: {{ v.toFixed(8) }}
        </div>
    </Card>
</template>
