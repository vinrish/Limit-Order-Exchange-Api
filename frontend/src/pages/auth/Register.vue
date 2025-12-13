<script setup lang="ts">
import { useAuthStore } from "@/stores/useAuthStore.ts";

const name = ref("");
const email = ref("");
const password = ref("");
const passwordConfirmation = ref();
const error = ref("");

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();

const handleRegister = async () => {
    const res = await $api("register", {
        method: "POST",
        body: {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: passwordConfirmation.value,
        },
        onResponseError({ response }) {
            error.value = response._data.errors;
        },
    });

    const { message, access_token, user } = res;

    auth.setAuth(access_token, user);

    const redirect = route.query.redirect as string | undefined;
    await router.replace(redirect || { name: "Profile" });

    // console.log(message, access_token, user);
};
</script>

<template>
    <AuthLayout>
        <Card class="bg-linear-to-br from-[#2b2b2f] via-[#2b2b2f] to-[#2b2b2f]">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <div class="text-3xl font-bold text-emerald-400">▲</div>
            </div>

            <h2 class="text-2xl font-semibold text-emerald-400 text-center">
                Create Account
            </h2>
            <p class="text-sm text-gray-400 text-center mb-6">Join Us Today.</p>

            <div
                v-if="error"
                class="bg-red-500/10 text-red-400 text-sm p-3 rounded mb-4"
            >
                {{ error }}
            </div>

            <form class="space-y-4" @submit.prevent="handleRegister">
                <!-- Name -->
                <div>
                    <label class="text-sm text-gray-400 mb-1 block">
                        Name
                    </label>
                    <input
                        v-model="name"
                        type="text"
                        placeholder="Vincent Kariuki"
                        class="w-full bg-[#2b2b2f] border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>

                <!-- Email -->
                <div>
                    <label class="text-sm text-gray-400 mb-1 block">
                        Email Address
                    </label>
                    <input
                        v-model="email"
                        type="email"
                        placeholder="admin@example.com"
                        class="w-full bg-[#2b2b2f] border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>

                <!-- Password -->
                <div>
                    <label class="text-sm text-gray-400 mb-1 block">
                        Password
                    </label>
                    <input
                        v-model="password"
                        type="password"
                        placeholder="********"
                        class="w-full bg-[#2b2b2f] border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>

                <!-- Password -->
                <div>
                    <label class="text-sm text-gray-400 mb-1 block">
                        Confirm Password
                    </label>
                    <input
                        v-model="passwordConfirmation"
                        type="password"
                        placeholder="********"
                        class="w-full bg-[#2b2b2f] border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    />
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-2.5 rounded-lg transition"
                >
                    Confirm
                </button>
            </form>

            <!-- Footer -->
            <p class="text-center text-sm text-gray-400 mt-6">
                Or
                <RouterLink
                    :to="{ name: 'Login' }"
                    class="text-emerald-400 hover:underline"
                >
                    Login
                </RouterLink>
            </p>
        </Card>
    </AuthLayout>
</template>
