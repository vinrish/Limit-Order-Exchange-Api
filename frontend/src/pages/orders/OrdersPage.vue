<script setup lang="ts">
import {
    type Order,
    type OrderBookSide,
    type OrdersApiResponse,
    type OrderStatus,
    useOrderStore
} from "@/stores/useOrderStore";
import { useProfileStore } from "@/stores/useProfileStore";

const orderStore = useOrderStore();
const profileStore = useProfileStore();

const symbol = ref("BTC");

await loadData();

async function loadData() {
    const { data: ordersResponse } = await useApi<Order[]>(createUrl(`orders`, { query: { symbol } }));

    if (ordersResponse.value) {
        // all orders from API
        const orders: Order[] = ordersResponse.value.map(o => ({
            id: o.id,
            symbol: o.symbol,
            side: o.side,
            price: parseFloat(o.price),
            amount: parseFloat(o.amount),
            status: o.status as OrderStatus,
        }));

        orderStore.setOrders(orders);

        const bids: OrderBookSide[] = orders
            .filter(o => o.side === 'buy')
            .sort((a, b) => b.price - a.price)
            .map(o => ({ price: o.price, amount: o.amount }));

        const asks: OrderBookSide[] = orders
            .filter(o => o.side === 'sell')
            .sort((a, b) => a.price - b.price)
            .map(o => ({ price: o.price, amount: o.amount }));

        orderStore.setOrderBook(bids, asks);
    }
}

const cancelling = ref<string | null>(null);

const cancelOrder = async (orderId: string) => {
    if (cancelling.value) return;

    cancelling.value = orderId;
    try {
        await useApi(createUrl(`orders/${orderId}/cancel`), { method: 'POST' });

        orderStore.updateOrderStatus(orderId, 3);
        await profileStore.loadProfile();
        await loadData();
    } catch (err) {
        console.error('Cancel failed', err);
    } finally {
        cancelling.value = null;
    }
};
</script>

<template>
    <Default>
        <div class="grid grid-cols-2 gap-6">
            <!-- Wallet -->
            <div class="bg-[#1e1e22] p-4 rounded">
                <h3 class="font-semibold mb-2 text-2xl">Wallet</h3>
                <p class="text-lg font-sans text-white mb-2">
                    USD:
                    <b>{{ profileStore.balances.usd.toFixed(2) }}</b>
                </p>
                <div v-for="(v, k) in profileStore.balances.assets" :key="k" class="text-lg font-sans text-white mb-2">
                    {{ k }}: {{ v }}
                </div>
            </div>

            <!-- Orderbook -->
            <div class="bg-[#1e1e22] p-4 rounded">
                <h3 class="font-semibold text-2xl mb-2">OrderBook ({{ symbol }})</h3>

                <div class="grid grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-red-400 text-xl">Asks</p>
                        <div
                            v-for="a in orderStore.orderBook.asks"
                            :key="a.price"
                            class="text-lg font-sans"
                        >
                            {{ a.price }} - {{ a.amount }}
                        </div>
                    </div>

                    <div>
                        <p class="text-green-400 text-xl">Bids</p>
                        <div
                            v-for="b in orderStore.orderBook.bids"
                            :key="b.price"
                            class="text-lg font-sans"
                        >
                            {{ b.price }} - {{ b.amount }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders -->
            <div class="bg-[#1e1e22] p-4 rounded">
                <h3 class="font-semibold mb-2 text-xl">My Orders</h3>

                <table class="w-full text-lg">
                    <thead>
                        <tr>
                            <td class="font-bold font-sans">Side</td>
                            <td class="font-bold font-sans">Price</td>
                            <td class="font-bold font-sans">Amount</td>
                            <td class="font-bold font-sans">Status</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="o in orderStore.orders" :key="o.id">
                            <td
                                class="text-lg font-bold"
                                :class="
                                    o.side === 'buy'
                                        ? 'text-green-400'
                                        : 'text-red-500'
                                "
                            >
                                {{ o.side }}
                            </td>
                            <td>{{ o.price }}</td>
                            <td>{{ o.amount }}</td>
                            <td class="mb-2">
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
                                    @click="cancelOrder(o.id)"
                                    :disabled="cancelling === o.id"
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 disabled:opacity-40 rounded text-white text-sm"
                                >
                                    Cancel
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </Default>
</template>
