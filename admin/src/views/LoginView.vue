<script setup lang="ts">
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { z } from 'zod';
import { Form, FormField } from '@primevue/forms';
import type { FormSubmitEvent } from '@primevue/forms';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Message from 'primevue/message';
import { ApiError } from '@/api/client';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const router = useRouter();
const auth = useAuthStore();

const schema = z.object({
    email: z.string().min(1, 'Email is required').email('Enter a valid email address'),
    password: z.string().min(1, 'Password is required'),
});

const resolver = zodResolver(schema);

const errorMessage = ref<string | null>(null);

async function submitLogin({ valid, values }: FormSubmitEvent): Promise<void> {
    if (!valid) return;

    errorMessage.value = null;

    try {
        await auth.login({
            email: values.email as string,
            password: values.password as string,
        });

        const redirect = typeof route.query.redirect === 'string' ?
            route.query.redirect :
            '/';

        await router.push(redirect);
    } catch (error) {
        errorMessage.value = error instanceof ApiError && error.status === 422 ?
            'The provided credentials do not match our records.' :
            'Could not log in. Please check your details and try again.';
    }
}
</script>

<template>
    <main class="flex min-h-screen items-center justify-center bg-slate-950 px-6 py-12 text-slate-100">
        <section class="w-full max-w-md">
            <div class="mb-8 text-center">
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-400">
                    Admin
                </p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-white">
                    Sign In
                </h1>
                <p class="mt-3 text-sm text-slate-400">
                    Use your administrator account to manage site content.
                </p>
            </div>

            <Form
                :resolver
                :initial-values="{ email: '', password: '' }"
                class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-2xl shadow-black/30"
                @submit="submitLogin"
            >
                <Message v-if="errorMessage" severity="error" class="mb-4">
                    {{ errorMessage }}
                </Message>

                <FormField v-slot="$field" name="email">
                    <label for="email" class="block text-sm font-medium text-slate-200">
                        Email address
                    </label>
                    <InputText
                        id="email"
                        v-bind="$field"
                        type="email"
                        autocomplete="email"
                        class="mt-2 w-full"
                    />
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <FormField v-slot="$field" name="password" class="mt-5">
                    <label for="password" class="block text-sm font-medium text-slate-200">
                        Password
                    </label>
                    <Password
                        id="password"
                        v-bind="$field"
                        :feedback="false"
                        toggle-mask
                        autocomplete="current-password"
                        class="mt-2 w-full"
                    />
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <Button
                    type="submit"
                    label="Sign In"
                    :loading="auth.isLoggingIn"
                    class="mt-6 w-full"
                />
            </Form>
        </section>
    </main>
</template>
