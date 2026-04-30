<script setup lang="ts">
import DndListItem from "~/components/lists/DndListItem.vue";

const emit = defineEmits(['selected']);

interface ListItem {
    label?: string
    name?: string
    slug?: string
    to?: string
}

const props = defineProps<{
    class?: string
    filter?: boolean
    items?: ListItem[]
    loading?: boolean
    selected?: string
    template: Component|string
}>();

const dndListMoreRef = ref<HTMLElement | null>(null);

const filterText = ref<string>('');

// Allow parent elements to access the ref.
defineExpose({ dndListMoreRef });
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

    <ul v-if="!loading" ref="dndListMoreRef" class="overflow-scroll h-full mb-4" :class="props.class">
        <DndListItem
            v-for="item in items?.filter(item =>
                filterText.length === 0 ? true : item.name?.toLowerCase().includes(filterText.toLowerCase())
            ) ?? []"
            :id="item.slug ?? ''"
            :key="item.slug"
            :label="item.name"
            :active="selected === item.slug"
            @click="$emit('selected', item)"
        >
            <component :is="template" :item="item"/>
        </DndListItem>
    </ul>

    <span v-else>Loading…</span>
</template>
