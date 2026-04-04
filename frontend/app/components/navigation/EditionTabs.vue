<script setup lang="ts">
import type {TabsItem} from "#ui/components/Tabs.vue";
import type {SpellEdition} from "~/classes/spells/spellEdition";

defineEmits(['edition-selected']);

const props = defineProps<{
    active?: SpellEdition
    class?: string
    editions: SpellEdition[],
    tabData: TabsItem[]
}>();

</script>

<template>
    <nav v-if="editions && tabData" class="edition-tabs flex flex-col gap-1" :class="props.class">
        <button
            v-for="tab in tabData"
            :key="tab.id"
            :disabled="tab.disabled"
            class="font-[mrs-eaves] uppercase block font-bold text-xl p-2 rounded-tl-lg rounded-b-lg text-center
                cursor-pointer"
            :class="[
                active?.id === tab.id ? 'active' : null,
                tab.disabled ? null : 'border-stripe',
                'border-' + tab.class,
                tab.disabled ? 'bg-gray-950 text-dimmed' : 'bg-' + tab.class + (tab.id === active?.id ? '-active' : '')
            ]"
            @click="$emit('edition-selected', tab.id)"
        >
            {{ tab.label }}
            <template v-if="tab.suffix">{{ tab.suffix }}</template>
        </button>
    </nav>
</template>
