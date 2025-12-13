import type { RouteRecordRaw } from "vue-router";
import Login from "@/pages/auth/Login.vue";
import Register from "@/pages/auth/Register.vue";
import Profile from "@/pages/profile/Profile.vue";
import OrderForm from "@/pages/orders/OrderForm.vue";

export const routes: RouteRecordRaw[] = [
    {
        path: "/",
        name: "Login",
        component: Login,
        meta: { guest: true },
    },

    {
        path: "/register",
        name: "Register",
        component: Register,
        meta: { guest: true },
    },

    {
        path: "/profile",
        name: "Profile",
        component: Profile,
        meta: { auth: true },
    },

    {
        path: "/order-form",
        name: "PlaceOrder",
        component: OrderForm,
        meta: { auth: true },
    },
];
