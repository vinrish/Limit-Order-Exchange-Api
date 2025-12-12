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
    }

    function logout() {
        token.value = null;
        user.value = null;
    }

    return {
        token,
        user,
        isAuthenticated,
        setAuth,
        logout,
    };
});
