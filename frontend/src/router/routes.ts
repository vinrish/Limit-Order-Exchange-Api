import type { RouteRecordRaw } from "vue-router";
import Login from "@/pages/auth/Login.vue";
import Register from "@/pages/auth/Register.vue";

export const routes: RouteRecordRaw[] = [
    {
        path: "/",
        name: "Login",
        component: Login,
    },

    {
        path: "/register",
        name: "Register",
        component: Register,
    },
];
