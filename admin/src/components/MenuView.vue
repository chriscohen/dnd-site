<script setup lang="ts">
import { useRouter } from 'vue-router';
import type { MenuItem } from "primevue/menuitem";
import Menu from 'primevue/menu';

const router = useRouter();

const items: MenuItem[] = [
    {
        label: 'Dashboard',
        command: () => router.push({ name: 'dashboard' }),
    },
    {
        label: 'Companies',
        command: () => router.push({ name: 'companies' }),
    },
];
</script>
<template>
    <aside class="hidden w-64 border-r border-slate-800 px-6 py-6 lg:block">
        <nav class="space-y-1">
            <Menu ref="menu" :model="items"/>
        </nav>
    </aside>
</template>
