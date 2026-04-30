<script setup lang="ts">
import DndHeading from "~/components/headings/DndHeading.vue";
import type {Person} from "~/classes/person";
import {useBookCreditCache} from "~/stores/Store";
import {type BookCreditApiResponse, createBookCredit} from "~/classes/sources/bookCredit";
import CreditsListItem from "~/components/credits/CreditsListItem.vue";
import BaseCard from "~/components/cards/BaseCard.vue";

const props = defineProps<{
    class?: string
    person?: Person
}>();

const store = useBookCreditCache();

const { pending } = await useLazyAsyncData(
    'credits',
    async () => await callOnce(() => store.page({ key: props.person?.slug ?? '' }))
);

const items = computed(() => (store.pagedItems as BookCreditApiResponse[]).map(createBookCredit))


</script>

<template>
    <BaseCard :class="props.class" class="self-start flex flex-col">
        <DndHeading underline size="2" underline-color="red-800" class="mb-2">Credits</DndHeading>
        <div v-if="!pending" class="overflow-y-scroll flex-1 min-h-0 mb-4">
            <CreditsListItem v-for="item in items" :key="item.id" :item="item"/>
            <div ref="productListRef"/>
        </div>

        <div v-else class="overflow-y-scroll flex-1 min-h-0">
            <USkeleton class="w-full h-12"/>
        </div>
    </BaseCard>
</template>
