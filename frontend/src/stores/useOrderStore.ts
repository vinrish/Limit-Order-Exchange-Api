export type OrderSide = "buy" | "sell";
export type OrderStatus = 1 | 2 | 3;

export interface Order {
    id: number;
    symbol: string;
    side: OrderSide;
    price: number;
    amount: number;
    status: OrderStatus;
}

export interface OrderBookSide {
    price: number;
    amount: number;
}

export const useOrderStore = defineStore("orders", () => {
    const orders = ref<Order[]>([]);

    const orderBook = ref<{
        bids: OrderBookSide[];
        asks: OrderBookSide[];
    }>({
        bids: [],
        asks: [],
    });

    function setOrders(data: Order[]) {
        orders.value = data;
    }

    function setOrderBook(bids: OrderBookSide[], asks: OrderBookSide[]) {
        orderBook.value.bids = bids;
        orderBook.value.asks = asks;
    }

    function onOrderMatched(payload: any) {
        const order = orders.value.find((o) => o.id === payload.order_id);
        if (order) order.status = 2;
    }

    return {
        orders,
        orderBook,
        setOrders,
        setOrderBook,
        onOrderMatched,
    };
});
