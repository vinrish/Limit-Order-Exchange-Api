<script setup lang="ts">
import { useOrderStore } from "@/stores/useOrderStore";

const props = defineProps<{ symbol: "BTC" | "ETH" }>();
const emit = defineEmits<{
    (e: "update:symbol", value: "BTC" | "ETH"): void;
}>();

const store = useOrderStore();
</script>

<template>
    <Card
        class="bg-linear-to-tb from-black via-[#2b2b2f] to-black p-4 rounded"
        style="width: 300px"
    >
        <div class="flex flex-col gap gap-6">
            <div class="flex gap-2 m-0">
                <button
                    v-for="s in ['BTC', 'ETH']"
                    :key="s"
                    class="px-4 py-2 rounded font-semibold"
                    :class="
                        props.symbol === s
                            ? 'bg-blue-600 text-white'
                            : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                    "
                    @click="emit('update:symbol', s)"
                >
                    {{ s }}
                </button>
            </div>
            <h3 class="font-semibold text-amber-500 text-2xl mb-2">
                OrderBook ({{ props.symbol }})
            </h3>
        </div>
        <div class="grid grid-cols-2 gap-6 text-sm">
            <div>
                <p class="text-red-400 text-xl">Asks</p>
                <div
                    v-for="a in store.orderBook.asks"
                    :key="a.price"
                    class="text-lg text-amber-400 font-sans"
                >
                    {{ a.price }} - {{ a.amount }}
                </div>
            </div>

            <div>
                <p class="text-green-400 text-xl">Bids</p>
                <div
                    v-for="b in store.orderBook.bids"
                    :key="b.price"
                    class="text-lg text-amber-400 font-sans"
                >
                    {{ b.price }} - {{ b.amount }}
                </div>
            </div>
        </div>
    </Card>
</template>
