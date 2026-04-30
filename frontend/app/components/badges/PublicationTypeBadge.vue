<script setup lang="ts">
import BadgeBase from "~/components/badges/BadgeBase.vue";

const props = defineProps<{
    type: string
}>();
</script>

<template>
    <UTooltip arrow text="An official spell" :delay-duration="0.25">
        <BadgeBase :label="props.type"/>
    </UTooltip>
</template>
