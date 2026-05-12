<script setup lang="ts">
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { z } from 'zod';
import { Form, FormField } from '@primevue/forms';
import type { FormSubmitEvent } from '@primevue/forms';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import Button from 'primevue/button';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Message from 'primevue/message';
import { ApiError } from 'dnd5e-api';
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
    <main class="flex min-h-screen items-center justify-center px-6 py-12">
        <section class="w-full max-w-md">
            <Card>
                <template #title>Sign In</template>
                <template #content>
                    <Form
                        :resolver
                        :initial-values="{ email: '', password: '' }"
                        @submit="submitLogin"
                    >
                        <Message v-if="errorMessage" severity="error" class="mb-4">
                            {{ errorMessage }}
                        </Message>

                        <FormField v-slot="$field" name="email">
                            <InputText
                                id="email"
                                v-bind="$field"
                                type="email"
                                autocomplete="email"
                                placeholder="Email"
                                fluid
                            />
                            <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                                {{ $field.error?.message }}
                            </Message>
                        </FormField>

                        <FormField v-slot="$field" name="password" class="mt-5">
                            <Password
                                id="password"
                                v-bind="$field"
                                :feedback="false"
                                toggle-mask
                                autocomplete="current-password"
                                placeholder="Password"
                                fluid
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
                </template>
            </Card>
        </section>
    </main>
</template>
