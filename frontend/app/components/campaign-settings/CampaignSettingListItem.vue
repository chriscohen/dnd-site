<script setup lang="ts">
import type {CampaignSetting} from "../../../../packages/@dnd5e";

const props = defineProps<{
    item: CampaignSetting
}>();
</script>

<template>
    <NuxtLink v-if="item.slug" :to="'/campaign-settings/' + item.slug" class="text-nowrap">
        {{ item.name }}
    </NuxtLink>
</template>
