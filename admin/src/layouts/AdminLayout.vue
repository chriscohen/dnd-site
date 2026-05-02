<script setup lang="ts">
import { ref, watch } from 'vue';
import { RouterView, useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Button from 'primevue/button';
import MenuView from '@/components/MenuView.vue';

const router = useRouter();
const route = useRoute();
const auth = useAuthStore();

const mobileMenuOpen = ref(false);

watch(route, () => { mobileMenuOpen.value = false; });

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

            <!-- Mobile menu overlay -->
            <div v-if="mobileMenuOpen" class="fixed inset-0 z-50 lg:hidden">
                <div class="absolute inset-0 bg-black/50" @click="mobileMenuOpen = false" />
                <div class="relative h-full w-64 border-r border-slate-800 bg-slate-900">
                    <button
                        class="absolute right-3 top-3 text-muted hover:text-white"
                        aria-label="Close menu"
                        @click="mobileMenuOpen = false"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <MenuView :mobile="true" />
                </div>
            </div>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="border-b border-slate-800 bg-slate-950/80 px-6 py-4 backdrop-blue">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <button
                                class="text-muted hover:text-white lg:hidden"
                                aria-label="Open menu"
                                @click="mobileMenuOpen = true"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div>
                                <p class="text-sm text-muted">
                                    Laravel API Admin
                                </p>
                                <h2 class="text-lg font-semibold text-white">
                                    Content Administration
                                </h2>
                            </div>
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
