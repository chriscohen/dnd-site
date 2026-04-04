<script setup lang="ts">
import TeaserTitle from "~/components/teasers/TeaserTitle.vue";
import TeaserTile from "~/components/teasers/TeaserTile.vue";
import type {Spell} from "~/classes/spells/spell";

const props = defineProps<{
    data?: Spell
}>();

const getSubtitle = computed(() => {
    return [
        toOrdinal(props?.data?.lowestLevel?.toString() ?? ''),
        'level',
        props?.data?.school
    ].join(' ');
});
</script>

<template>
    <TeaserTile :href="'/spells/' + data?.slug">
        <NuxtImg
            v-if="data?.image?.url"
            :src="data?.image?.url"
            class="absolute top-0 left-0"
        />
        <TeaserTitle :title="data?.name" :subtitle="getSubtitle" :rarity="data?.rarity"/>
        <BadgesBadgeContainer vertical class="absolute top-4, right-0">
            <BadgesGameEditionBadge
                v-for="edition in data?.editions"
                :key="edition.id"
                :edition="edition.gameEdition"
                rounded-corners="left"
            />
        </BadgesBadgeContainer>
    </TeaserTile>
</template>
