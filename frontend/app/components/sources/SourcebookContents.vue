<script setup lang="ts">
import {
    createSourceContents,
    type SourceContentsApiResponse
} from "~/classes/sources/sourceContents";
import DndHeading from "~/components/headings/DndHeading.vue";
import {useSourceContentsCache} from "~/stores/Store";

const props = defineProps<{
    class?: string
    source?: string
}>();
const store = useSourceContentsCache();

// Track the open state of the collapsible and watch it so we can execute the API request.
const open: Ref<boolean> = ref(false);

watch (open, (isNowOpen) => {
    if (isNowOpen && !data.value) execute();
});

// useLazyAsyncData to only populate the source contents if the user opens the collapsible.
const { pending, data, execute } = await useLazyAsyncData(
    'contents',
    async () => await store.get({ key: props.source ?? '' }) as Promise<SourceContentsApiResponse[]|undefined>,
    { immediate: false }
);

const contents = computed(() => data.value?.map(createSourceContents));
</script>

<template>
    <UCollapsible v-model:open="open" class="h-full group">
        <DndHeading size="2" underline-color="red-800" underline class="flex w-full justify-between cursor-pointer group">
            Contents

            <template #controls>
                <UIcon
                    v-if="!pending"
                    name="i-lucide-chevron-up"
                    class="group group-data-[state=open]:rotate-180 text-4xl transition-transform duration-300"
                />
                <UIcon v-else name="i-lucide-loader-circle" class="animate-spin"
                />
            </template>
        </DndHeading>

        <template #content>
            <template v-for="item in contents ?? []" :key="item?.id">
                <DndHeading
                    size="3"
                    class="font-[mrs-eaves] uppercase mt-4 mb-2"
                    :underline="true"
                >
                    {{ item.name }}
                </DndHeading>

                <ul v-if="item.hasHeaders()" class="block">
                    <li v-for="header in item.headers ?? []"  :key="header.header">
                        {{ header }}
                    </li>
                </ul>
            </template>
        </template>
    </UCollapsible>
</template>
