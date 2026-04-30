<script setup lang="ts">
import Spell from "~/components/spells/Spell.vue";
import EditionTabs from "~/components/navigation/EditionTabs.vue";
import SpellbookExtra from "~/components/spells/SpellbookExtra.vue";
import PageTitle from "~/components/labels/PageTitle.vue";
import DndSection from "~/components/containers/DndSection.vue";
import BottomNavigationEditions from "~/components/navigation/BottomNavigationEditions.vue";
import type {TabsItem} from "#ui/components/Tabs.vue";
import {createSpell, type SpellApiResponse} from "~/classes/spells/spell";
import type {SpellEdition} from "~/classes/spells/spellEdition";

const route = useRoute();
const store = useSpellCache();
const data: SpellApiResponse = await store.get({ key: route.params.slug as string }) as SpellApiResponse;
const item = createSpell(data);

useHead({ title: item?.name ?? 'Loading' });
definePageMeta({ layout: false });

const tabData = ref<TabsItem[]>([
    {
        id: "1e",
        class: "edition-1",
        label: "1st",
        disabled: !item?.editions?.find((edition: SpellEdition) => edition.gameEdition === '1e'),
    },
    {
        id: "2e",
        class: "edition-2",
        label: "2nd",
        disabled: !item?.editions?.find((edition: SpellEdition) => edition.gameEdition === '2e'),
    },
    {
        id: "3e",
        class: "edition-3",
        label: "3rd",
        disabled: !item?.editions?.find((edition: SpellEdition) => edition.gameEdition === '3e'),
    },
    {
        id: "3.5",
        class: "edition-3",
        label: "3.5",
        disabled: !item?.editions?.find((edition: SpellEdition) => edition.gameEdition === '3.5'),
    },
    {
        id: "4e",
        class: "edition-4",
        label: "4th",
        disabled: !item?.editions?.find((edition: SpellEdition) => edition.gameEdition === '4e'),
    },
    {
        id: "5e (2014)",
        class: "edition-5",
        label: "5th",
        disabled: !item?.editions?.find((edition: SpellEdition) => edition.gameEdition === '5e (2014)'),
    },
    {
        id: "5e (2024)",
        class: "edition-2024",
        label: "2024",
        disabled: !item?.editions?.find((edition: SpellEdition) => edition.gameEdition === '5e (2024)'),
    }
]);
</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle :title="item?.name ?? 'Title'" back-to="/spells" :underline="true"/>
        </template>

        <div class="">

        </div>

        <DndSection v-if="item" class="book-container spellbook">
            <EditionTabs
                class="hidden sm:block"
                :editions="item?.editions ?? []"
                :tab-data="tabData"
                @edition-selected="(id: string) => false"
            />
            <Spell :spell="item" :edition="item?.editions?.[0]"/>
        </DndSection>

        <NuxtImg v-if="item?.image" :src="item.image?.url" :name="item.name" class="w-full rounded-xl"/>

        <div class="book-container spellbook-extras">
            <SpellbookExtra :spell="item" :edition="item?.editions?.[0]"/>
        </div>

        <template #bottomNav>
            <BottomNavigationEditions :tab-data="tabData" class="sm:hidden"/>
        </template>
    </NuxtLayout>
</template>

