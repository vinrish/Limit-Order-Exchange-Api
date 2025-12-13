import { getEcho } from "@/plugins/echo";
import { useOrderStore } from "@/stores/useOrderStore.ts";
import { useProfileStore } from "@/stores/useProfileStore.ts";

export const useRealtimeStore = defineStore("realtime", {
    state: () => ({
        userChannel: null as string | null,
        userId: null as string | null,
    }),

    actions: {
        subscribeUser(userId: string) {
            const channel = `user.${userId}`;

            if (this.userChannel === channel) return;

            const echo = getEcho();
            const orders = useOrderStore();
            const profile = useProfileStore();

            echo.private(channel).listen("OrderMatched", (e: any) => {
                orders.onOrderMatched(e);
                profile.applyTrade(e);
            });

            this.userChannel = channel;
            this.userId = userId;
        },

        unsubscribeUser() {
            if (!this.userChannel) return;

            getEcho().leave(this.userChannel);
            this.userChannel = null;
            this.userId = null;
        },

        // 🔁 Called on Echo reconnect
        resubscribe() {
            if (!this.userId) return;

            console.info("[Realtime] Resubscribing user channel");
            this.unsubscribeUser();
            this.subscribeUser(this.userId);
        },
    },
});
