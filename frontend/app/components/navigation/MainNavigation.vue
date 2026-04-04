<script setup lang="ts">
const route = useRoute();
const active = ref<string>('Home');

watch(
    () => route.fullPath,
    () => active.value = route.path,
);

type RouteData = {
    name: string
    path?: string
    icon?: string
    children?: RouteData[]
};

const routeData = ref<RouteData[]>([
    { name: 'Home', path: '/' },
    {
        name: 'Spells',
        path: '/spells',
        icon: 'spell',
        children: [
            { name: 'Magic Schools', path: '/magic-schools' }
        ]
    },
    { name: 'Items', path: '/items' },
    {
        name: 'Classes',
        path: '/classes',
        children: [
            { name: 'Feats', path: '/' },
            { name: 'Skills', path: '/'}
        ]
    },
    {
        name: 'Creatures',
        path: '/creature-types',
    },
    {
        name: 'Sources',
        path: '/sources',
        children: [
            { name: 'Magazines', path: '/' },
            { name: 'Websites', path: '/' }
        ]
    },
    { name: 'Campaign Settings', path: '/campaign-settings' }
]);

function getActive(item: RouteData): boolean {
    if (active.value === '/') {
        return item.path === '/';
    } else {
        return item.path === active.value;
    }
}

const menuOpen: Ref<boolean> = ref<boolean>(false);

</script>

<template>
    <nav role="navigation" class="relative flex space-between">
        <!-- Main navigation, full width -->
        <ul id="main-navigation" class="font-eaves pt-4 mb-4 text-xl hidden lg:flex">
            <li
                v-for="item in routeData"
                :key="item.name"
                class="group cursor-pointer hover:bg-highlight hover:text-gray-900 transition-bg-color duration-500 ease-in-out"
            >
                <NuxtLink
                    :to="item.path"
                    :class="getActive(item) ? 'active' : ''"
                    class="text-nowrap px-4 py-2"
                >
                    {{ item.name }}
                </NuxtLink>

                <template v-if="item.children">
                    <ul
                        class="absolute min-w-64 bg-slate-800 hidden
                            group-hover:block group-hover:text-white z-50"
                    >
                        <li
                            v-for="inner in item.children"
                            :key="inner.name"
                            class="block hover:bg-highlight hover:text-gray-900"
                        >
                            <NuxtLink
                                v-if="inner.path"
                                :to="inner.path"
                                :class="getActive(inner) ? 'active' : ''"
                                class="block px-4 py-2"
                            >
                                {{ inner.name }}
                            </NuxtLink>
                        </li>
                    </ul>
                </template>


            </li>
        </ul>

        <button
            class="lg:hidden ml-4"
            aria-label="Toggle menu"
            @click="menuOpen = !menuOpen"
        >
            <svg style="width: 2rem; height: 2rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path v-if="!menuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div
            v-show="menuOpen"
            class="lg:hidden absolute top-full left-0 bg-slate-800 z-100"
        >
            <ul class="flex flex-col transition-opacity duration-500 ease-in-out">
                <li
                    v-for="item in routeData"
                    :key="item.name"
                >
                    <NuxtLink
                        :to="item.path"
                        class="block text-nowrap uppercase text-3xl font-[mrs-eaves] px-8 py-4 hover:bg-slate-700"
                        @click="menuOpen = false"
                    >
                        <UIcon v-if="item.icon" :name="'i-dnd-spellbook'" class="text-white mr-2"/>
                        {{ item.name }}
                    </NuxtLink>
                </li>
            </ul>
        </div>
    </nav>
</template>
