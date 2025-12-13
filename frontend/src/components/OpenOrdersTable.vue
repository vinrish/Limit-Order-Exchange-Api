<script setup lang="ts">
import { useOrderStore } from "@/stores/useOrderStore";

const store = useOrderStore();

const emit = defineEmits<{
    (e: "cancel", orderId: string): void;
}>();
</script>

<template>
    <Card
        class="bg-linear-to-bl from-black via-[#2b2b2f] to-black card p-4 rounded"
    >
        <h3 class="font-semibold text-amber-400 mb-2 text-xl">My Orders</h3>

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
                <tr v-for="o in store.openOrders" :key="o.id">
                    <td
                        class="text-lg font-bold"
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
                    <td>
                        <button
                            v-if="o.status === 1"
                            class="px-3 py-1 bg-red-600 hover:bg-red-700 disabled:opacity-40 rounded text-white text-sm"
                            @click="emit('cancel', o.id)"
                        >
                            Cancel
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </Card>
</template>
