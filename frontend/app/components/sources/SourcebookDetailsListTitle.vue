<script setup lang="ts">
const props = defineProps({
    label: { type: String, required: true },
});
</script>

<template>
    <h5 class="font-[Mrs_Eaves] text-nowrap text-base">{{ props.label }}</h5>
</template>
