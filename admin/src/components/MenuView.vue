<script setup lang="ts">
import { useRouter } from 'vue-router';
import type { MenuItem } from "primevue/menuitem";
import Menu from 'primevue/menu';

withDefaults(defineProps<{ mobile?: boolean }>(), { mobile: false });

const router = useRouter();

const items: MenuItem[] = [
    {
        label: 'Dashboard',
        command: () => router.push({ name: 'dashboard' }),
    },
    {
        label: 'Campaign Settings',
        command: () => router.push({ name: 'campaign-settings' }),
    },
    {
        label: 'Companies',
        command: () => router.push({ name: 'companies' }),
    },
    {
        label: 'People',
        command: () => router.push({ name: 'people' }),
    },
    {
        label: 'Sources',
        command: () => router.push({ name: 'sources' }),
    },
    {
        label: 'Spells',
        command: () => router.push({ name: 'spells' }),
    },
    {
        label: 'Spell Editions',
        command: () => router.push({ name: 'spell-editions' }),
    }
];
</script>
<template>
    <aside v-if="!mobile" class="hidden w-64 border-r border-slate-800 px-6 py-6 lg:block">
        <nav class="space-y-1">
            <Menu :model="items" />
        </nav>
    </aside>
    <nav v-else class="px-6 py-8">
        <Menu :model="items" />
    </nav>
</template>
