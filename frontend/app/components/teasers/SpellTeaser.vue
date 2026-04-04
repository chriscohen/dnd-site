<script setup lang="ts">
import SpellTeaserTile from "~/components/teasers/SpellTeaserTile.vue";
import type {Spell} from "~/classes/spells/spell";

interface Props {
    data?: Spell,
    variant?: string
}

const { data = undefined, variant = 'tile' } = defineProps<Props>();
</script>

<template>
    <SpellTeaserTile v-if="variant == 'tile'" :data="data"/>
</template>
