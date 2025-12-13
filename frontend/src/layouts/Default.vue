<script setup lang="ts">
import { useAuthStore } from "@/stores/useAuthStore.ts";
import { type ProfileData, useProfileStore } from "@/stores/useProfileStore.ts";
import { useToastStore } from "@/stores/useToastStore.ts";
import { useRealtimeStore } from "@/stores/useRealTimeStore.ts";

defineProps<{ title?: string }>();

const auth = useAuthStore();
const router = useRouter();
const toast = useToastStore();

const profileStore = useProfileStore();

const { data: profileData } = await useApi<ProfileData>(createUrl("profile"));

if (profileData.value) {
    profileStore.setProfile(profileData.value);
}

const profile = computed(() => profileStore.profileData);

const realtime = useRealtimeStore();

if (profileData.value) {
    profileStore.setProfile(profileData.value);
    realtime.subscribeUser(profileData.value.id);
}

const logout = async () => {
    await auth.logout();

    toast.show("Logged out successfully");

    await router.replace({ name: "Login" });
};
</script>

<template>
    <div
        class="min-h-screen flex bg-linear-to-br from-black via-[#2b2b2f] to-black text-amber-400"
    >
        <!-- Sidebar -->
        <aside
            class="w-64 bg-emerald-950 rounded-tr-3xl text-amber-50 p-6 flex flex-col fixed h-screen"
        >
            <h2 class="text-xl font-bold font-serif mb-6 text-emerald-850/25">
                {{ title || "Limit Order Exchange Engine" }}
            </h2>
            <nav class="flex-1 overflow-auto">
                <ul>
                    <RouterLink
                        class="block h-8 bg-white mb-2 rounded-md hover:bg-emerald-900 hover:text-white text-black pl-2 text-lg"
                        to="/profile"
                        >Wallet Overview</RouterLink
                    >
                    <RouterLink
                        to="/order-form"
                        class="block h-8 bg-white mb-2 rounded-md hover:bg-emerald-900 hover:text-white text-black pl-2 text-lg"
                        >Place Order</RouterLink
                    >
                </ul>
            </nav>
            <button
                class="mt-auto bg-red-600 hover:bg-red-700 py-2 rounded-lg font-medium transition"
                @click="logout"
            >
                Logout
            </button>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-64 h-screen">
            <!-- Header -->
            <header
                class="bg-blend-normal text-emerald-400 p-4 flex justify-between items-center fixed w-[calc(100%-16rem)] z-10"
            >
                <span class="font-semibold text-xl ml-8">{{
                    profile?.name || "User"
                }}</span>
                <span class="text-sm text-gray-100">{{
                    profile?.email || ""
                }}</span>
            </header>

            <!-- Main router content -->
            <main class="flex-1 mt-16 mb-16 p-6 overflow-auto">
                <slot />
            </main>

            <!-- Footer -->
            <footer
                class="bg-black/80 text-gray-100 p-4 text-center fixed bottom-0 w-[calc(100%-16rem)]"
            >
                &copy; 2025 Limit Order Exchange
            </footer>
        </div>
    </div>
</template>

<style scoped>
a:hover {
    transition: color 0.2s;
}
button {
    transition: background-color 0.2s;
}
</style>
