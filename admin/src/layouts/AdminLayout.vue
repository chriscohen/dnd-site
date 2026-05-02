<script setup lang="ts">
import { RouterView, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Button from 'primevue/button';
import MenuView from '@/components/MenuView.vue';

const router = useRouter();
const auth = useAuthStore();

async function logout(): Promise<void> {
    await auth.logout();
    await router.push({
        name: 'login',
    });
}
</script>

<template>
    <div class="min-h-screen">
        <div class="flex min-h-screen">
            <MenuView />

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="border-b border-slate-800 bg-slate-950/80 px-6 py-4 backdrop-blue">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-400">
                                Laravel API Admin
                            </p>
                            <h2 class="text-lg font-semibold text-white">
                                Content Administration
                            </h2>
                        </div>

                        <div v-if="auth.user" class="hidden text-right sm:block">
                            {{ auth.user.email }}
                        </div>

                        <Button
                            type="button"
                            label="Sign Out"
                            :loading="auth.isLoggingOut"
                            @click="logout"
                        >
                        </Button>
                    </div>
                </header>

                <main class="flex-1 px-6 py-8">
                    <div class="mx-auto max-w-7xl">
                        <RouterView />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
