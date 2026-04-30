<script setup lang="ts">
import type {CreatureType} from "~/classes/creatures/creatureType";
import BadgeContainer from "~/components/badges/BadgeContainer.vue";
import GameEditionBadge from "~/components/badges/GameEditionBadge.vue";
import ChallengeRatingLabel from "~/components/labels/ChallengeRatingLabel.vue";

const props = defineProps<{
    item: CreatureType
}>();
</script>

<template>
    <li class="group">
        <NuxtLink
            :to="'/creature-types/' + item.slug"
            class="group-hover:bg-highlight px-4 py-2 flex flex-row"
        >
            <div class="flex flex-col">
                <h3 class="font-modesto group-hover:text-dark">{{ item.name }}</h3>

                <span class="italic group-hover:text-dark">{{ item.sizeAndType() }}</span>

                <BadgeContainer>
                    <GameEditionBadge v-for="edition in item.editions" :key="edition.id" :edition="edition.gameEdition"/>
                </BadgeContainer>
            </div>

            <ChallengeRatingLabel
                v-if="item.defaultEdition()?.challengeRating"
                class="hidden sm:block ml-auto text-nowrap font-scaly text-xl group-hover:text-dark"
                :cr="item.defaultEdition()?.challengeRating ?? 0"
            />
        </NuxtLink>
    </li>
</template>

<style lang="scss">
@use '~/assets/css/default/backgrounds';
</style>
