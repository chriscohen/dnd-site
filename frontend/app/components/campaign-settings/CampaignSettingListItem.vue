<script setup lang="ts">
import type {CampaignSetting} from "~/classes/campaignSetting";

const props = defineProps<{
    item: CampaignSetting
}>();
</script>

<template>
    <NuxtLink v-if="item.slug" :to="'/campaign-settings/' + item.slug" class="text-nowrap">
        {{ item.name }}
    </NuxtLink>
</template>
