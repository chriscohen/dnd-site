<script setup lang="ts">
import PageTitle from "~/components/labels/PageTitle.vue";
import BottomNavigation from "~/components/navigation/BottomNavigation.vue";
import StatBlock from "~/components/dnd/StatBlock.vue";
import {createCreatureType, type CreatureTypeApiResponse} from "~/classes/creatures/creatureType";
import TabsCard from "~/components/cards/TabsCard.vue";

const route = useRoute();
const store = useCreatureTypeCache();
const data: CreatureTypeApiResponse = await store.get({ key: route.params.slug as string }) as CreatureTypeApiResponse;
const item = createCreatureType(data);

useHead({ title: item.name ?? 'Loading' });
definePageMeta({ layout: false });
</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle :title="item.name ?? 'Loading'" back-to="/creature-types">
                <template #subtitle>{{ item?.editions?.[0]?.type?.mainType?.name }}</template>
            </PageTitle>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 mt-4 md:px-4">
            <TabsCard v-if="item" class="" :tabs="item?.toTabs()">
                <StatBlock :creature="item" :edition="item?.editions?.[0]"/>
            </TabsCard>
        </div>

        <template #bottomNav>
            <BottomNavigation :items="[]"/>
        </template>
    </NuxtLayout>
</template>
