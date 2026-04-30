<script setup lang="ts">
const props = defineProps<{
    class?: string
}>();
</script>

<template>
    <USkeleton :class="props?.class"/>
</template>
