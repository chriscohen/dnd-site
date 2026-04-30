<script setup lang="ts">
import {useMagicSchoolCache} from "~/stores/Store";
import {createMagicSchool} from "~/classes/magic/magicSchool";
import MagicSchoolTeaser from "~/components/magic-schools/MagicSchoolTeaser.vue";
import PageTitle from "~/components/labels/PageTitle.vue";

const store = useMagicSchoolCache();

const { data } = useLazyAsyncData(
    'magic-schools',
    async () => await store.page()
);
const items = computed(() => data.value?.map(createMagicSchool));

useHead({ title: 'Magic Schools' });
definePageMeta({ layout: false });
</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle title="Magic Schools" back-to="/" :underline="true"/>
        </template>

        <div class="h-full overflow-y-scroll">
            <ClientOnly>
                <section
                    v-if="items"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 w-full gap-4
                    overflow-scroll"
                >
                    <MagicSchoolTeaser v-for="item in items" :key="item.id" :item="item"/>
                </section>
            </ClientOnly>
        </div>
    </NuxtLayout>
</template>
