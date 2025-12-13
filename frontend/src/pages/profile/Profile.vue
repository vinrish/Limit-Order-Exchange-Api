<script setup lang="ts">
import {
    type Order,
    type OrderBookSide,
    type OrderStatus,
    useOrderStore,
} from "@/stores/useOrderStore";
import { useProfileStore } from "@/stores/useProfileStore";

const orderStore = useOrderStore();
const profileStore = useProfileStore();

const symbol = ref<"BTC" | "ETH">("BTC");

watch(
    symbol,
    async () => {
        await loadData();
    },
    { immediate: true },
);

async function loadData() {
    orderStore.setOpenOrders([]);
    orderStore.setAllOrders([]);
    orderStore.setOrderBook([], []);

    const { data: bookResponse } = await useApi<Order[]>(
        createUrl(`orders`, {
            query: { symbol },
        }),
    );

    if (bookResponse.value) {
        // all bookorders from API
        const openOrders: Order[] = bookResponse.value.map((o) => ({
            id: o.id,
            symbol: o.symbol,
            side: o.side,
            price: parseFloat(o.price),
            amount: parseFloat(o.amount),
            status: o.status as OrderStatus,
        }));

        orderStore.setOpenOrders(openOrders);

        const bids: OrderBookSide[] = openOrders
            .filter((o) => o.side === "buy")
            .sort((a, b) => b.price - a.price)
            .map((o) => ({ price: o.price, amount: o.amount }));

        const asks: OrderBookSide[] = openOrders
            .filter((o) => o.side === "sell")
            .sort((a, b) => a.price - b.price)
            .map((o) => ({ price: o.price, amount: o.amount }));

        orderStore.setOrderBook(bids, asks);
    }

    const { data: myOrdersResponse } = await useApi<Order[]>(
        createUrl(`all-orders`, {
            query: { symbol: symbol.value },
        }),
    );

    if (myOrdersResponse.value) {
        orderStore.setAllOrders(
            myOrdersResponse.value.map((o) => ({
                id: o.id,
                symbol: o.symbol,
                side: o.side,
                price: parseFloat(o.price),
                amount: parseFloat(o.amount),
                status: o.status as OrderStatus,
            })),
        );
    }
}

const cancelling = ref<string | null>(null);

const cancelOrder = async (orderId: string) => {
    if (cancelling.value) return;

    cancelling.value = orderId;
    try {
        await useApi(createUrl(`orders/${orderId}/cancel`), { method: "POST" });

        orderStore.updateOrderStatus(orderId, 3);
        await profileStore.loadProfile();
        await loadData();
    } catch (err) {
        console.error("Cancel failed", err);
    } finally {
        cancelling.value = null;
    }
};
</script>

<template>
    <Default>
        <div>
            <UserProfile />

            <div class="grid grid-cols-3 gap gap-3">
                <OrderBookCard v-model:symbol="symbol" class="p-4 ml-4" />

                <OpenOrdersTable class="mr-10" @cancel="cancelOrder" />

                <AllOrdersTable />
            </div>
        </div>
    </Default>
</template>

<style scoped></style>
