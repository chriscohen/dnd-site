<script setup lang="ts">
const props = defineProps<{
    class?: string
}>();
</script>

<template>
    <div
        class="page-content md:mx-4 mt-4 grid gap-4"
        :class="props.class"
    >
        <slot/>
    </div>
</template>
