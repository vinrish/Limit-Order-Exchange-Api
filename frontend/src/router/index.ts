import { createRouter, createWebHistory } from "vue-router";
import { routes } from "./routes";

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to) => {
    const isAuthenticated = !!localStorage.getItem("token");

    if (to.meta.auth && !isAuthenticated) {
        return { name: "Login" };
    }

    if (to.meta.guest && isAuthenticated) {
        return { name: "Dashboard" };
    }
});

export default router;
