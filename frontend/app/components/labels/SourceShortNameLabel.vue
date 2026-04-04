<script setup lang="ts">
const props = defineProps<{
    tooltip: string
    shortName: string
    to?: string
}>();
</script>

<template>
    <UTooltip :text="tooltip" :content="{ side: 'top' }">
        <ULink v-if="to" :to="to" class="link">{{ props.shortName }}</ULink>
        <span v-else>{{ props.shortName }}</span>
    </UTooltip>
</template>
