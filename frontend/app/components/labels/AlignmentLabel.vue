<script setup lang="ts">
import type {Alignment} from "~/classes/alignment/alignment";

const props = defineProps<{
    alignment?: Alignment
}>();
</script>

<template>
    <span>{{ alignment?.alignment ?? 'Unknown Alignment' }}</span>
</template>
