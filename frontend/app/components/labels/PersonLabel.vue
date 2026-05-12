<script setup lang="ts">
import type {Person} from "../../../../packages/@dnd5e";

const props = defineProps<{
    class?: string
    link?: boolean
    person?: Person
}>();
</script>

<template>
    <span v-if="person" :class="props.class">
        {{ person.firstName }}
        {{ person.lastName }}
    </span>
</template>
