<script setup lang="ts">
import PageTitle from "~/components/labels/PageTitle.vue";
import {createMagicSchool, type MagicSchoolApiResponse} from "~/classes/magic/magicSchool";
import BaseCard from "~/components/cards/BaseCard.vue";
import DndHeading from "~/components/headings/DndHeading.vue";
import MagicSchoolImage from "~/components/magic-schools/MagicSchoolImage.vue";
import type {DropdownMenuItem} from "#ui/components/DropdownMenu.vue";

const route = useRoute();
const store = useMagicSchoolCache();
const data = await store.get({ key: route.params.slug as string }) as MagicSchoolApiResponse;
const item = computed(() => createMagicSchool(data));

// Get all magic school data for the switcher.
const { data: switcherData } = useLazyAsyncData(
    'magic-schools',
    async () => await store.page()
);

// Map magic schools to DropdownMenuItems.
const switcherItems = computed(() => switcherData.value?.map((item: MagicSchoolApiResponse) => {
    const magicSchool = createMagicSchool(item);
    return {
        icon: 'i-magic-school-' + magicSchool.id,
        label: magicSchool.name,
        to: '/magic-schools/' + magicSchool.id,
        type: "link"
    } as DropdownMenuItem
}));

const pageTitle = computed(() => item.value?.name ?? 'Loading');

useHead({ title: pageTitle });
definePageMeta({ layout: false });
</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle :title="pageTitle" back-to="/magic-schools" :switcher-items="switcherItems">
                <template #subtitle>Magic School</template>
            </PageTitle>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-start">
            <BaseCard v-if="item.description">
                <div class="flex flex-row mt-2 mb-4 gap-8 items-center">
                    <MagicSchoolImage :school="item?.id ?? ''" class="w-24 min-w-24 h-24 max-h-24"/>
                    <div class="prose" v-html="item.description"/>
                </div>
            </BaseCard>

            <BaseCard v-if="item?.hasChildren">
                <DndHeading size="2" underline class="mb-2">Subschools</DndHeading>

                <ul class="flex flex-col gap-4">
                    <li v-for="child in item.children" :key="child.id">
                        <DndHeading size="3">{{ child.name }}</DndHeading>
                        <div class="prose" v-html="child.description"/>
                    </li>
                </ul>
            </BaseCard>
        </div>
    </NuxtLayout>
</template>
