<script setup lang="ts">
import {useCreatureMainTypeCache} from "~/stores/Store";
import {
    createCreatureMainType,
    type CreatureMainType,
    type CreatureMainTypeApiResponse
} from "~/classes/creatures/creatureMainType";
import PopoverBase from "~/components/popovers/PopoverBase.vue";

const props = defineProps<{
    slug?: string
}>();

const store = useCreatureMainTypeCache();
const item: Ref<CreatureMainType | null> = ref<CreatureMainType | null>(null);

async function onOpen(isOpen: boolean) {
    if (!props.slug) return;
    const data: CreatureMainTypeApiResponse = await store.get({ key: props.slug }) as CreatureMainTypeApiResponse;
    item.value = createCreatureMainType(data);
}

</script>

<template>
    <PopoverBase
        :title="item?.name"
        :content="item?.description()"
        :loading="store.isLoading"
        @popover-open="onOpen"
    >
        <slot/>
    </PopoverBase>
</template>
