<script setup lang="ts">
import type {CampaignSetting} from "~/classes/campaignSetting";
import BaseCard from "~/components/cards/BaseCard.vue";
import MediaImage from "~/components/media/MediaImage.vue";

const props = defineProps<{
    item: CampaignSetting
}>();
</script>

<template>
    <BaseCard opacity="50" class="hover:bg-black/100 transition-colors duration-500 ease-in-out">
        <NuxtLink
            :to="'/campaign-settings/' + item.slug"
            class="w-full h-full flex justify-center items-center"
        >
            <MediaImage
                v-if="item.logo"
                :media="item.logo"
                :alt="item.name + ' logo'"
                class="w-full"
            />
            <h3 v-else class="text-center">{{ item.name }}</h3>
        </NuxtLink>
    </BaseCard>
</template>
