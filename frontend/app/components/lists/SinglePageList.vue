<script setup lang="ts">
import DndListItem from "~/components/lists/DndListItem.vue";

const emit = defineEmits(['selected']);

export interface ListItem {
    label?: string
    name?: string
    slug?: string
    to?: string
    template?: HTMLElement
}

const props = defineProps<{
    class?: string
    filter?: boolean
    items?: ListItem[]
    routeParam: string
}>();

const route = useRoute();
const router = useRouter();

// Expose the ref to the parent.
const singlePageListRef = ref(null);
defineExpose({ singlePageListRef });

const selected = computed(() => route.query?.[props.routeParam] as string | undefined);
const filterText = ref<string>('');

const filteredItems = computed(() => {
    if (!props.items) return [];

    if (props.filter && filterText.value.length > 0) {
        return props.items.filter(item => item.name?.toLowerCase().includes(filterText.value.toLowerCase()))
    }

    return props.items;
});

function handleSelect(item: ListItem) {
    router.replace({ query: { ...route.query, s: item.slug } });
    emit('selected', item);
}
</script>

<template>
    <UInput
        v-if="filter"
        v-model="filterText"
        variant="ghost"
        placeholder="Filter…"
        class="w-full border-b-2 border-red-800 [&>input]:text-lg mb-2"
    >
        <template v-if="filterText.length > 0" #trailing>
            <UButton
                color="neutral"
                variant="link"
                size="lg"
                icon="i-lucide-x"
                class="cursor-pointer"
                @click="filterText = ''"
            />
        </template>
    </UInput>

    <ul ref="singlePageListRef" class="overflow-scroll h-full mb-4" :class="props.class">
        <DndListItem
            v-for="item in filteredItems"
            :id="item.slug ?? ''"
            :key="item.slug"
            :label="item.name"
            :active="selected === item.slug"
            @click="handleSelect(item)"
        />
    </ul>
    <slot/>
</template>
