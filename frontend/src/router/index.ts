import {
    createRouter,
    createWebHistory,
    type RouteRecordRaw,
} from "vue-router";
import { routes } from "./routes";
import { useAuthStore } from "@/stores/useAuthStore.ts";

const validRouteNames = routes
    .map((r: RouteRecordRaw) => r.name)
    .filter(Boolean);

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to) => {
    const auth = useAuthStore();

    if (to.meta.auth && !auth.isAuthenticated) {
        return { name: "Login", query: { redirect: to.fullPath } };
    }

    if (to.meta.guest && auth.isAuthenticated) {
        return { name: "Profile" };
    }

    if (to.fullPath.startsWith("http") || to.fullPath.startsWith("//")) {
        console.warn("Blocked navigation to external URL:", to.fullPath);

        return false;
    }

    // ----- Secure route check -----
    if (to.name && !validRouteNames.includes(to.name as string)) {
        console.warn(
            "Blocked navigation to an invalid/external route:",
            to.fullPath,
        );

        return false;
    }
});

export default router;
