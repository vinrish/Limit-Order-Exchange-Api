<script setup lang="ts">
const email = ref("");
const password = ref("");
const remember = ref();
const error = ref("");

const handleLogin = async () => {};
</script>

<template>
    <AuthLayout>
        <Card>
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <div class="text-3xl font-bold text-emerald-400">▲</div>
            </div>

            <h2 class="text-2xl font-semibold text-emerald-400 text-center">
                Sign In
            </h2>
            <p class="text-sm text-gray-400 text-center mb-6">
                Welcome back to your account.
            </p>

            <div
                v-if="error"
                class="bg-red-500/10 text-red-400 text-sm p-3 rounded mb-4"
            >
                {{ error }}
            </div>

            <form class="space-y-4" @submit.prevent="handleLogin">
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

                <!-- Remember + Forgot -->
                <div
                    class="flex items-center justify-between text-sm text-gray-400"
                >
                    <label class="flex items-center gap-2">
                        <input
                            v-model="remember"
                            type="checkbox"
                            class="accent-emerald-500"
                        />
                        Remember Me
                    </label>

                    <a href="#" class="hover:text-emerald-400">
                        Forgot Password?
                    </a>
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
                    :to="{ name: 'Register' }"
                    class="text-emerald-400 hover:underline"
                >
                    Create an account
                </RouterLink>
            </p>
        </Card>
    </AuthLayout>
</template>
