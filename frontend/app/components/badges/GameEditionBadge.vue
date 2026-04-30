<script setup lang="ts">
import BadgeBase from "~/components/badges/BadgeBase.vue";
import {getGameEdition} from "~/classes/gameEdition";

const props = defineProps<{
    edition?: string
}>();

const gameEdition = getGameEdition(props?.edition ?? '');

</script>

<template>
    <BadgeBase
        type="edition"
        :class="gameEdition?.className"
        rounded
        :extra-text="gameEdition?.name"
        :label="gameEdition?.shortName"
        :bg-color="gameEdition?.className"
    />
</template>

<style scoped lang="scss">
@use '~/assets/css/default/backgrounds';
</style>
