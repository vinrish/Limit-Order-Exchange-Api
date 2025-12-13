import { destroyEcho, initEcho } from "@/plugins/echo.ts";
import { useRealtimeStore } from "@/stores/useRealTimeStore.ts";

export const useAuthStore = defineStore("auth", () => {
    const token = useCookie<string | null>("access_token", {
        default: () => null,
        sameSite: "lax",
        secure: import.meta.env.PROD,
    });

    const user = ref<any | null>(null);

    const isAuthenticated = computed(() => !!token.value);

    function setAuth(accessToken: string, authUser?: any) {
        token.value = accessToken;
        user.value = authUser ?? null;

        const realtime = useRealtimeStore();

        initEcho(accessToken, () => {
            realtime.resubscribe();
        });
    }

    async function logout() {
        try {
            await $api("logout", {
                method: "POST",
            });
        } finally {
            token.value = null;
            user.value = null;

            const realtime = useRealtimeStore();
            realtime.unsubscribeUser();

            destroyEcho();
        }
    }

    return {
        token,
        user,
        isAuthenticated,
        setAuth,
        logout,
    };
});
