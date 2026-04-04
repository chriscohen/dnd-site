<script setup lang="ts">
import type {SpellEdition} from "~/classes/spells/spellEdition";

const props = defineProps<{
    edition?: SpellEdition
}>();
</script>

<template>
    <span v-if="edition">
        <template v-if="!edition.hasSavingThrow">None</template>

        <template v-else>{{ edition.savingThrow?.type }} {{ edition.savingThrow?.multiplier }}</template>
    </span>
</template>
