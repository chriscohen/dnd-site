<script setup lang="ts">
import type {Alignment} from "../../../../packages/@dnd5e";

const props = defineProps<{
    alignment?: Alignment
}>();
</script>

<template>
    <span>{{ alignment?.alignment ?? 'Unknown Alignment' }}</span>
</template>
