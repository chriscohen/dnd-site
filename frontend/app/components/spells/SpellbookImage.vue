<script setup lang="ts">
import type {Spell} from "~/classes/spells/spell";

const props = defineProps<{
    spell?: Spell
}>();
</script>

<template>
    <img
        v-if="spell"
        class="spellbook-image"
        :src="spell?.image?.url"
        :alt="spell?.name + ' preview image'"
    >
</template>

<style lang="scss" scoped>

.spellbook-image {
    position: absolute;
    top: 8rem;
    left: 0;
    right: 0;
    margin: auto;
}
</style>
