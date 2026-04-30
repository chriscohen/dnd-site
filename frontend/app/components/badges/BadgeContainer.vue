<script setup lang="ts">
const props = defineProps<{
    class?: string
    vertical?: boolean
}>();

const classes = [
    props.class,
    'badge-container',
    props.vertical ? 'vertical' : null,
]
</script>

<template>
    <aside :class="classes"><slot/></aside>
</template>

<style scoped lang="scss">
.badge-container {
    display: flex;
    gap: 0.25rem;

    &.vertical {
        flex-direction: column;
        align-items: end;
    }
}
</style>
