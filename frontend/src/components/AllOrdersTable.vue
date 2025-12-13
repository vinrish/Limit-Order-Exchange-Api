<script setup lang="ts">
import { useOrderStore } from "@/stores/useOrderStore";

const store = useOrderStore();
</script>

<template>
    <Card
        class="bg-linear-to-tl from-black via-[#2b2b2f] to-black card p-4 rounded"
    >
        <h3 class="font-semibold text-amber-400 mb-2 text-xl">All Orders</h3>

        <table class="w-full text-lg">
            <thead>
                <tr>
                    <td class="font-bold text-amber-400 font-sans">Side</td>
                    <td class="font-bold text-amber-400 font-sans">Price</td>
                    <td class="font-bold text-amber-400 font-sans">Amount</td>
                    <td class="font-bold text-amber-400 font-sans">Status</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="o in store.allOrders" :key="o.id">
                    <td
                        class="text-lg text-amber-400 font-bold"
                        :class="
                            o.side === 'buy' ? 'text-green-400' : 'text-red-500'
                        "
                    >
                        {{ o.side }}
                    </td>
                    <td class="text-amber-400">{{ o.price }}</td>
                    <td class="text-amber-400">{{ o.amount }}</td>
                    <td class="mb-2 text-amber-400">
                        {{
                            o.status === 1
                                ? "Open"
                                : o.status === 2
                                  ? "Filled"
                                  : "Cancelled"
                        }}
                    </td>
                </tr>
            </tbody>
        </table>
    </Card>
</template>
