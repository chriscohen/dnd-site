<script setup lang="ts">
import { RouterLink, RouterView, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const auth = useAuthStore();

const navigationItems = [
    {
        label: 'Dashboard',
        to: '/'
    },
    {
        label: 'Creatures',
        to: '/creatures'
    },
    {
        label: 'Sources',
        to: '/sources'
    }
];

async function logout(): Promise<void> {
    await auth.logout();
    await router.push({
        name: 'login',
    });
}
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <div class="flex min-h-screen">
            <aside class="hidden w-64 border-r border-slate-800 bg-slate-900/70 px-6 py-6 lg:block">
                <RouterLink to="/" class="block">
                    <p class="text-sm font-semibold uppercase tracking-wide">
                        Admin
                    </p>
                    <h1 class="mt-1 text-xl font-bold tracking-tight text-white">
                        Content Manager
                    </h1>
                </RouterLink>

                <nav class="mt-10 space-y-1">
                    <RouterLink
                        v-for="item in navigationItems"
                        :key="item.to"
                        :to="item.to"
                        class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-300 transition hover:bg-slate-800 hover:text-white"
                        active-class="bg-indigo-500/15 text-indigo-300"
                    >
                        {{ item.label }}
                    </RouterLink>
                </nav>
            </aside>

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

                        <div class="flex items-center gap-4">
                            <div v-if="auth.user" class="hidden text-right text-sm sm:block">
                                <p class="font-medium text-white">
                                    {{ auth.user.name }}
                                </p>
                                <p class="text-slate-400">
                                    {{ auth.user.email }}
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            :disabled="auth.isLoggingOut"
                            class="rounded-lg border border-slate-700 px-3 py-2 text-sm front-medium text-slate-200
                                transition hover:border-slate-500 hover:bg-slate-800"
                            @click="logout"
                        >
                            <span v-if="auth.isLoggingOut">Logging out…</span>
                            <span v-else>Log out</span>
                            Logout
                        </button>
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
