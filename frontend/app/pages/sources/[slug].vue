<script setup lang="ts">
import GameEditionBadge from "~/components/badges/GameEditionBadge.vue";
import PublicationTypeBadge from "~/components/badges/PublicationTypeBadge.vue";
import SourceDetailsList from "~/components/sources/SourceDetailsList.vue";
import ProductLinkButtonContainer from "~/components/containers/ProductLinkButtonContainer.vue";
import PageTitle from "~/components/labels/PageTitle.vue";
import SourcebookContents from "~/components/sources/SourcebookContents.vue";
import ProseContainer from "~/components/text/ProseContainer.vue";
import BottomNavigation from "~/components/navigation/BottomNavigation.vue";
import SourceCredits from "~/components/sources/SourceCredits.vue";
import MediaImage from "~/components/media/MediaImage.vue";
import {createSource, type SourceApiResponse} from "~/classes/sources/source";
import BaseCard from "~/components/cards/BaseCard.vue";

const route = useRoute();
const store = useSourceCache();
const runtime = useRuntimeConfig();

const uiStore = useUiStore();
uiStore.setBackgroundImage('library.avif');

const { pending, data } = useLazyAsyncData(
    'source',
    async () => await store.get({ key: route.params.slug as string }) as Promise<SourceApiResponse|undefined>
);
const item = computed(() => createSource(data.value));

const pageTitle = computed(() => ((!item.value || store.isLoading) ?
    'Loading' :
    item.value?.name) + ' | ' + runtime.public.siteName);
useHead({ title: pageTitle });
definePageMeta({ layout: false });
</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <!-- Heading -->
            <PageTitle
                :loading="pending"
                :title="item?.name ?? ''"
                back-to="/sources"
                :underline="true"
            >
                <template #labels>
                    <GameEditionBadge v-if="item?.gameEdition" :edition="item?.gameEdition as string"/>
                    <PublicationTypeBadge v-if="item?.publicationType" :type="item?.publicationType as string" />
                </template>
                <template #subtitle>
                    {{ item?.sourceType }}
                </template>
            </PageTitle>
            <!-- /Heading -->
        </template>

        <div
            class="grid grid-cols-1 sm:grid-cols-2 sm:grid-rows-1 items-start lg:grid-cols-3 gap-4 mx-2 sm:h-full
                overflow-y-scroll sm:overflow-y-hidden"
        >
            <div id="column-left" class="gap-4 sm:h-full">
                <a id="overview"/>
                <MediaImage :loading="pending" :media="item.coverImage" :name="item.name" rounded class="w-full"/>
                <SourceDetailsList :loading="pending" :source="item" class="mt-4"/>
                <ProductLinkButtonContainer
                    v-if="(item?.primaryEdition()?.productIds?.length ?? 0) > 1"
                    :source="item"
                />
            </div>

            <div id="column-mid" class="h-full">
                <a id="description"/>
                <ProseContainer
                    v-if="item.description"
                    class="sm:max-h-full flex flex-col"
                    :loading="pending"
                    :markdown="item.description"
                    size="xl"
                />
            </div>

            <div id="column-right" class="flex flex-col w-full gap-4 sm:h-full">
                <BaseCard v-if="item?.editions?.[0]?.hasCredits">
                    <SourceCredits :source="item?.slug"/>
                </BaseCard>

                <a id="contents"/>
                <BaseCard v-if="item?.editions?.[0]?.hasContents">
                    <SourcebookContents :source="item.slug"/>
                </BaseCard>
                <!-- /Right Side -->
            </div>
            <!-- /Right Side -->
        </div>

        <template #bottomNav>
            <BottomNavigation
                :items="[
                    {
                        anchor: 'overview',
                        icon: 'image',
                        name: 'Overview'
                    },
                    {
                        anchor: 'description',
                        icon: 'notebook-text',
                        name: 'Description',
                        disabled: !item.description
                    },
                    {
                        anchor: 'contents',
                        icon: 'list',
                        name: 'Contents',
                        disabled: !item.editions?.[0]?.hasContents
                    },
                    {
                        anchor: 'credits',
                        icon: 'users',
                        name: 'Credits',
                        disabled: !item.editions?.[0]?.hasCredits
                    }
                ]"
            />
        </template>
    </NuxtLayout>
</template>

