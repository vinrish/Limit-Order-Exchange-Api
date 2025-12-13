import Echo from "laravel-echo";
import Pusher from "pusher-js";

// @ts-ignore
let echo: Echo | null = null;

type ReconnectHandler = () => void;
let onReconnect: ReconnectHandler | null = null;

export function initEcho(token: string, reconnectHandler?: ReconnectHandler) {
    if (echo) return echo;

    window.Pusher = Pusher;

    echo = new Echo({
        broadcaster: "pusher",
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        encrypted:true,
        enabledTransports: ["ws", "wss"],
        authEndpoint: `${import.meta.env.VITE_API_URL}/broadcasting/auth`,
        auth: {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        },
    });

    onReconnect = reconnectHandler ?? null;

    // 🔁 Reconnect handling
    const pusher = (echo.connector as any).pusher;

    pusher.connection.bind("connected", () => {
        console.info("[Echo] Connected");
        onReconnect?.();
    });

    pusher.connection.bind("disconnected", () => {
        console.warn("[Echo] Disconnected");
    });

    pusher.connection.bind("error", (err: any) => {
        console.error("[Echo] Error", err);
    });

    return echo;
}

export function destroyEcho() {
    if (!echo) return;

    echo.disconnect();
    echo = null;
    onReconnect = null;
}

export function getEcho(): Echo {
    if (!echo) throw new Error("Echo not initialized");
    return echo;
}
