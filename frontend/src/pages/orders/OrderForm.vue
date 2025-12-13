<script setup lang="ts">
const router = useRouter();

const symbol = ref("BTC");
const side = ref<"buy" | "sell">("buy");
const price = ref(0);
const amount = ref(0);

const submit = async () => {
    await $api("orders", {
        method: "POST",
        body: {
            symbol: symbol.value,
            side: side.value,
            price: price.value,
            amount: amount.value,
        },
    });

    router.push("profile");
};
</script>

<template>
    <Default>
        <div class="min-h-0 flex justify-center">
            <div
                class="bg-linear-to-br from-black via-[#1e1e22] to-black p-6 rounded w-120"
            >
                <h3 class="font-semibold mb-4 text-2xl">Place Order</h3>

                <select
                    v-model="symbol"
                    class="w-full bg-[#2b2b2f] border border-gray-700 px-3 py-2 rounded"
                >
                    <option>BTC</option>
                    <option>ETH</option>
                </select>

                <select
                    v-model="side"
                    class="w-full bg-[#2b2b2f] border border-gray-700 px-3 py-2 rounded mt-2"
                >
                    <option value="buy">Buy</option>
                    <option value="sell">Sell</option>
                </select>

                <input
                    v-model.number="price"
                    type="number"
                    class="w-full bg-[#2b2b2f] border border-gray-700 px-3 py-2 rounded mt-2"
                    placeholder="Price"
                />
                <input
                    v-model.number="amount"
                    type="number"
                    class="w-full bg-[#2b2b2f] border border-gray-700 px-3 py-2 rounded mt-2"
                    placeholder="Amount"
                />

                <button
                    class="mt-4 w-full bg-emerald-600 text-black text-2xl py-2 rounded hover:bg-emerald-950 hover:text-white"
                    @click="submit"
                >
                    Submit
                </button>
            </div>
        </div>
    </Default>
</template>

<style scoped></style>
