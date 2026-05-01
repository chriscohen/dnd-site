import { computed, ref } from 'vue';
import { defineStore } from "pinia";
import {apiRequest, getCsrfCookie} from "@/api/client.ts";

type User = {
    id: number;
    name: string;
    email: string;
};

type LoginCredentials = {
    email: string;
    password: string;
};

export const useAuthStore = defineStore('auth', () => {
    const user = ref<User | null>(null);
    const hasLoadedUser = ref(false);
    const isLoadingUser = ref(false);
    const isLoggingIn = ref(false);
    const isLoggingOut = ref(false);

    const isAuthenticated = computed(() => user.value !== null && user.value.id > 0);

    async function loadUser(): Promise<User | null> {
        if (isLoadingUser.value) {
            return user.value;
        }

        isLoadingUser.value = true;

        try {
            user.value = await apiRequest<User>('/api/user');
        } catch {
            user.value = null;
        } finally {
            hasLoadedUser.value = true;
            isLoadingUser.value = false;
        }

        return user.value;
    }

    async function login(credentials: LoginCredentials): Promise<void> {
        isLoggingIn.value = true;

        try {
            await getCsrfCookie();

            await apiRequest<void>('/login', {
                method: 'POST',
                json: credentials
            });

            await loadUser();
        } finally {
            isLoggingIn.value = false;
        }
    }

    async function logout(): Promise<void> {
        isLoggingOut.value = true;

        try {
            await apiRequest<void>('/logout', {
                method: 'POST'
            });
        } finally {
            user.value = null;
            hasLoadedUser.value = true;
            isLoggingOut.value = false;
        }
    }

    return {
        user,
        hasLoadedUser,
        isLoadingUser,
        isLoggingIn,
        isLoggingOut,
        isAuthenticated,
        loadUser,
        login,
        logout,
    };
});
