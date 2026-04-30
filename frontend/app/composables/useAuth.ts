export const useAuth = () => {
    const user = useState('user', () => null);
    const config = useRuntimeConfig();

    const login = async (credentials: any) => {
        await $fetch(`${config.public.apiBase}/sanctum/csrf-cookie`);

        await $fetch(`${config.public.apiBase}/login`, {
            method: 'POST',
            body: credentials
        });

        await fetchUser();
    };

    const fetchUser = async () => {
        try {
            const data = await $fetch(`${config.public.apiBase}/api/user`);
            user.value = data;
        } catch (e: any) {
            user.value = null;
        }
    };

    const logout = async () => {
        await $fetch(`${config.public.apiBase}/logout`, { method: 'POST' });
        user.value = null;
        navigateTo('/login');
    };

    return {
        fetchUser,
        login,
        logout,
        user
    }
}
