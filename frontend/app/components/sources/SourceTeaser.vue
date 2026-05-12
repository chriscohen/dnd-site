<script lang="ts" setup>

import TeaserTile from "~/components/teasers/TeaserTile.vue";
import type {Source} from "../../../../packages/@dnd5e";

const props = defineProps<{
    source?: Source
}>();
</script>

<template>
    <TeaserTile
        :edition="source?.gameEdition"
        :href="'/sources/' + source?.slug"
        :media="source?.coverImage"
        :name="source?.name"
    />
</template>
