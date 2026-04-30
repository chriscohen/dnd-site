<script setup lang="ts">
import SpellTeaser from "~/components/teasers/SpellTeaser.vue";
import {useSpellCache} from "~/stores/Store";
import PageTitle from "~/components/labels/PageTitle.vue";
import TeaserGrid from "~/components/teasers/TeaserGrid.vue";
import {createSpell, type Spell} from "~/classes/spells/spell";

const store = useSpellCache();
const spellMoreRef = ref<HTMLElement | null>(null);

const { data, execute } = await useLazyAsyncData(
    'spells',
    async () => await store.page(),
    {
        immediate: false
    }
);
const items = computed(() => data.value?.map(createSpell));

onMounted(() => execute());

useHead({ title: 'Spells' });
definePageMeta({ layout: false });
</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle title="Spells" back-to="/" :underline="true"/>
        </template>

        <TeaserGrid v-if="items">
            <SpellTeaser
                v-for="item in items"
                :key="item.id"
                :data="item"
            />
            <a ref="spellMoreRef"/>
        </TeaserGrid>
    </NuxtLayout>
</template>
