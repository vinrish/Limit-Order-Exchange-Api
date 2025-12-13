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
</script>

<template>
    <Default>
        <div class="grid grid-cols-3 gap-6">
            <!-- Wallet -->
            <div class="bg-[#1e1e22] p-4 rounded">
                <h3 class="font-semibold mb-2">Wallet</h3>
                <p>
                    USD:
                    <b>{{ profileStore.balances.usd.toFixed(2) }}</b>
                </p>
                <div v-for="(v, k) in profileStore.balances.assets" :key="k">
                    {{ k }}: {{ v }}
                </div>
            </div>

            <!-- Orderbook -->
            <div class="bg-[#1e1e22] p-4 rounded">
                <h3 class="font-semibold mb-2">OrderBook ({{ symbol }})</h3>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-red-400">Asks</p>
                        <div
                            v-for="a in orderStore.orderBook.asks"
                            :key="a.price"
                        >
                            {{ a.price }} — {{ a.amount }}
                        </div>
                    </div>

                    <div>
                        <p class="text-green-400">Bids</p>
                        <div
                            v-for="b in orderStore.orderBook.bids"
                            :key="b.price"
                        >
                            {{ b.price }} — {{ b.amount }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders -->
            <div class="bg-[#1e1e22] p-4 rounded">
                <h3 class="font-semibold mb-2">My Orders</h3>

                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th>Side</th>
                            <th>Price</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="o in orderStore.orders" :key="o.id">
                            <td
                                :class="
                                    o.side === 'buy'
                                        ? 'text-green-400'
                                        : 'text-red-400'
                                "
                            >
                                {{ o.side }}
                            </td>
                            <td>{{ o.price }}</td>
                            <td>{{ o.amount }}</td>
                            <td>
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
            </div>
        </div>
    </Default>
</template>
