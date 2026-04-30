<script setup lang="ts">
import CharacterClassTeaser from "~/components/characterClasses/CharacterClassTeaser.vue";
import {useCharacterClassCache} from "~/stores/Store";
import PageTitle from "~/components/labels/PageTitle.vue";
import {createCharacterClass} from "~/classes/characterClasses/characterClass";
import {useUiStore} from "~/stores/uiStore";

const uiStore = useUiStore();
uiStore.setBackgroundImage('d20-lady.avif');

const store = useCharacterClassCache();
const { data } = useLazyAsyncData(
    'character-classes',
    async () => await store.page()
)
const items = computed(() => data.value?.map(createCharacterClass));

useHead({ title: 'Classes' });
definePageMeta({ layout: false });
</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle title="Classes" back-to="/" :underline="true"/>
        </template>

        <div class="page-container my-4 flex flex-col xl:flex-row overflow-y-scroll">
            <ClientOnly>
                <div
                    v-if="items"
                    class="left-container grid grid-cols-[repeat(auto-fit,minmax(16rem,1fr))] gap-4 gap-y-8"
                >
                    <CharacterClassTeaser
                        v-for="item in items.filter(item => !item.isPrestige)"
                        :key="item.id"
                        :data="item"
                    />
                </div>
            </ClientOnly>

            <div class="right-container md:w-1/2"/>
        </div>
    </NuxtLayout>
</template>

