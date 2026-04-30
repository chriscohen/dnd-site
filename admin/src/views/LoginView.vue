<script setup lang="ts">
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { ApiError } from '@/api/client';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();

const email = ref<string>('');
const password = ref<string>('');
const errorMessage = ref<string | null>(null);

async function submitLogin(): Promise<void> {
    errorMessage.value = null;

    try {
        await auth.login({
            email: email.value,
            password: password.value
        });

        const redirect = typeof route.query.redirect === 'string' ?
            route.query.redirect :
            '/';

        await router.push(redirect);
    } catch (error) {
        if (error instanceof ApiError && error.status === 422) {
            errorMessage.value = 'The provided credentials do not match our records.';
            return;
        }
    }

    errorMessage.value = 'Could not log in. Please check your details and try again.';
}
</script>

<template>
    <main class="flex min-h-screen items-center justify-center bg-slate-950 px-6 py-12 text-slate-100">
        <section class="w-full max-w-md">
            <div class="mb-8 text-center">
                <p class="text-sm font-semibold  uppercase tracking-wide text-indigo-400">
                    Admin
                </p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-white">
                    Sign In
                </h1>
                <p class="mt-3 text-sm text-slate-400">
                    Use your administrator account to manage site content.
                </p>
            </div>

            <form
                class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-2xl shadow-black/30"
                @submit.prevent="submitLogin"
            >
                <div
                    v-if="errorMessage"
                    class="mb-4 rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-200"
                >
                    {{ errorMessage }}
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-200">
                        Email address
                    </label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        autocomplete="email"
                        required
                        class="mt-2 block w-full rounded-lg bborder border-slate-700 bg-slate-950 px-3 py-2
                            text-slate-100 outline-none transition placeholder:text-slate-500 focus:ring-2
                            focus:ring-indigo-400/20"
                    />
                </div>

                <div class="mt-5">
                    <label for="password" class="block text-sm font-medium text-slate-200">
                        Password
                    </label>
                    <input
                        id="password"
                        v-model="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        class="mt-2 block w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2
                            text-slate-100 outline-none transition placeholder:text-slate-500 focus:ring-2
                            focus:ring-indigo-400/20"
                        placeholder="********"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="auth.isLoggingIn"
                    class="mt-6 inline-flex w-full items-center justify-center rounded-lg bg-indigo-500 px-4 py-2.5
                        text-sm font-semibold"
                >
                    <span v-if="auth.isLoggingIn">Signing in…</span>

                    <span v-else>Sign In</span>
                </button>
            </form>
        </section>
    </main>
</template>
