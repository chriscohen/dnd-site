<script setup lang="ts">
import {useCreatureTypeCache} from "~/stores/Store";
import PageTitle from "~/components/labels/PageTitle.vue";
import {createCreatureType, type CreatureType} from "~/classes/creatures/creatureType";
import {useInfiniteScroll} from "@vueuse/core";
import {useUiStore} from "~/stores/uiStore";
import BaseCard from "~/components/cards/BaseCard.vue";
import CreatureTypeListItem from "~/components/creature-types/CreatureTypeListItem.vue";

const runtime = useRuntimeConfig();
const store = useCreatureTypeCache();
const uiStore = useUiStore();
uiStore.setBackgroundImage('demon.avif');

useHead({ title: 'Creatures' });
definePageMeta({ layout: false });

const items = ref<CreatureType[]>([]);

const { data, execute, status } = useLazyAsyncData(
    'creature-types',
    async () => await store.page(),
    {
        immediate: false
    }
);
const loadMore = async () => {
    if (status.value === 'pending') return;

    await execute();

    if (data.value) {
        items.value = [...data.value.map(createCreatureType)];
    }
}

loadMore();

onMounted(() => {
    useInfiniteScroll(
        loadMoreRef,
        () => loadMore(),
        {
            canLoadMore: () => store.getPage()?.hasMore ?? false,
            distance: runtime.public.infiniteScrollDistance as number,
            interval: runtime.public.infiniteScrollInterval as number
        }
    );
});

const loadMoreRef = useTemplateRef('loadMoreRef');
</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle title="Creatures" back-to="/"/>
        </template>

        <div class="flex flex-col gap-4 overflow-hidden h-full">
            <BaseCard class="max-h-full">
                <ClientOnly>
                    <ul class="h-full overflow-y-scroll">
                        <CreatureTypeListItem
                            v-for="item in items"
                            :key="item.slug"
                            :item="item"
                        />
                        <li ref="loadMoreRef"/>
                    </ul>
                </ClientOnly>
            </BaseCard>
        </div>
    </NuxtLayout>
</template>
