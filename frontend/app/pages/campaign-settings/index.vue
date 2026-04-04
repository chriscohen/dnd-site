<script setup lang="ts">
import PageTitle from "~/components/labels/PageTitle.vue";
import BaseCard from "~/components/cards/BaseCard.vue";
import TeaserGrid from "~/components/teasers/TeaserGrid.vue";
import {createCampaignSetting} from "~/classes/campaignSetting";
import CampaignSettingTeaser from "~/components/campaign-settings/CampaignSettingTeaser.vue";

const store = useCampaignSettingCache();
const data = await store.page();
const items = data?.map(createCampaignSetting);

useHead({ title: 'Campaign Settings' });
definePageMeta({ layout: false })

</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle title="Campaign Settings" back-to="/"/>
        </template>

        <div class="flex-1 min-h-0 flex flex-row gap-4">
            <TeaserGrid>
                <CampaignSettingTeaser v-for="item in items" :key="item.slug" :item="item"/>
            </TeaserGrid>
        </div>
    </NuxtLayout>
</template>
