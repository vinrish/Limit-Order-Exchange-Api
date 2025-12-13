<script setup lang="ts">
const isFallbackStateActive = ref(false);
</script>

<template>
    <div
        class="bg-linear-to-br from-black via-[#2b2b2f] to-black text-amber-400 flex flex-col"
    >
        <Toast />

        <!-- Main Content -->
        <main class="bg-[#2b2b2f]">
            <Suspense
                :timeout="0"
                @fallback="isFallbackStateActive = true"
                @resolve="isFallbackStateActive = false"
            >
                <template #default>
                    <RouterView />
                </template>

                <template #fallback>
                    <div
                        class="flex justify-center items-center h-full text-gray-400 text-lg"
                    >
                        Loading...
                    </div>
                </template>
            </Suspense>
        </main>
    </div>
</template>

<style scoped></style>
