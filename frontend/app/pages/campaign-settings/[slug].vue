<script setup lang="ts">
import {useCampaignSettingCache} from "~/stores/Store";
import PageTitle from "~/components/labels/PageTitle.vue";
import CampaignSettingCard from "~/components/campaign-settings/CampaignSettingCard.vue";
import {type CampaignSettingApiResponse, createCampaignSetting} from "~/classes/campaignSetting";
import BaseCard from "~/components/cards/BaseCard.vue";
import ProductList from "~/components/lists/ProductList.vue";
import {createSource} from "~/classes/sources/source";
import LinkList from "~/components/lists/LinkList.vue";
import CampaignSettingListItem from "~/components/campaign-settings/CampaignSettingListItem.vue";

const runtime = useRuntimeConfig();
const store = useCampaignSettingCache();
const sourceStore = useSourceCache();
const route = useRoute();

// List of campaign settings.
const { data: campaignSettingsData } = useLazyAsyncData(
    'campaign-settings',
    async () => await store.page()
);
const campaignSettings = computed(() => campaignSettingsData.value?.map(createCampaignSetting));

const campaignSettingData = await store.get({
    key: route.params.slug as string
}) as CampaignSettingApiResponse;
const item = computed(() => createCampaignSetting(campaignSettingData));

const pageTitle = computed(() => (
    (!item.value || store.isLoading) ?
        'Loading' :
        item.value?.name + ' Campaign Setting') + ' | ' + runtime.public.siteName
);
useHead({ title: pageTitle });
definePageMeta({
    key: (route) => route.fullPath,
    layout: false
});

// Get products for this campaign setting.
const productsData = await sourceStore.page({
    params: { 'campaign-setting': route.params.slug as string ?? '' }
});
const products = computed(() => productsData?.map(createSource));

</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle title="Campaign Settings" back-to="/campaign-settings"/>
        </template>

        <div class="flex-1 min-h-0 flex flex-row gap-4">
            <BaseCard>
                <LinkList
                    :items="campaignSettings"
                    :template="CampaignSettingListItem"
                    filter
                    class="w-xs"
                    :selected="item?.slug"
                />
            </BaseCard>

            <div class="grid gap-4 grid-cols-1 lg:grid-cols-2 grow content-start">
                <div class="w-full md:w-initial">
                    <CampaignSettingCard :loading="store.isLoading" :item="item" class="animate-fade w-full"/>
                </div>

                <ProductList
                    :products="products"
                    heading-size="3"
                />
            </div>
        </div>
    </NuxtLayout>
</template>
