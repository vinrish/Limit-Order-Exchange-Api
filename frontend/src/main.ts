import { createApp } from "vue";
import { createPinia } from "pinia";
import "./style.css";
import App from "./App.vue";
import router from "@/router";
import { useAuthStore } from "@/stores/useAuthStore.ts";
import { initEcho } from "@/plugins/echo.ts";

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);

const auth = useAuthStore();

if (auth.token) {
    initEcho(auth.token);
}

app.mount("#app");
