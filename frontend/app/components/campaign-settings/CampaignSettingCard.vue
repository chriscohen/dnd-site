<script setup lang="ts">
import ProseSkeleton from "~/components/loading/ProseSkeleton.vue";
import DndHeading from "~/components/headings/DndHeading.vue";
import type {CampaignSetting} from "~/classes/campaignSetting";
import BaseCard from "~/components/cards/BaseCard.vue";

const props = defineProps<{
    class?: string
    item?: CampaignSetting
    loading?: boolean
}>();

const { render } = useMarkdown();
const mdDescription = computed(() => render(props.item?.description));

</script>

<template>
    <BaseCard :class="props.class" class="animate-fade">
        <DndHeading size="2" underline>
            <USkeleton v-if="loading || !item" class="w-full h-8 mb-2"/>
            <template v-else>{{ item.name }}</template>

            <template v-if="item?.publisher" #subtitle>
                <NuxtLink :to="'/companies/' + item.publisher?.slug" class="italic">{{ item.publisher?.name }}</NuxtLink>
            </template>
        </DndHeading>

        <NuxtImg v-if="item?.logo?.url" :src="item.logo.url" :alt="item.name + ' logo'" class="mt-4 mx-auto"/>

        <ProseSkeleton v-if="!item" class="mt-4"/>
        <div v-if="item?.description" class="mt-4 prose prose-gray" v-html="mdDescription"/>
    </BaseCard>
</template>
