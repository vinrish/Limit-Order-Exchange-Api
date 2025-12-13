export const useToastStore = defineStore("toast", () => {
    const message = ref("");
    const type = ref<"success" | "error" | "info">("info");
    const visible = ref(false);

    const show = (
        msg: string,
        t: "success" | "error" | "info" = "info",
        duration = 3000,
    ) => {
        message.value = msg;
        type.value = t;
        visible.value = true;
        setTimeout(() => (visible.value = false), duration);
    };

    return { message, type, visible, show };
});
