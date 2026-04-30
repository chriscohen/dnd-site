<script setup lang="ts">
const props = defineProps({
    label: { type: String, required: true },
});
</script>

<template>
    <div class="text=sm text-nowrap text-right">{{ props.label }}</div>
</template>
