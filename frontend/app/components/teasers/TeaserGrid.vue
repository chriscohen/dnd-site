<script setup lang="ts">
const props = defineProps<{
    class?: string
    columnWidth?: string
}>();

const width = (props.columnWidth ?? '150') + 'px';
const classes = [
    props.class,
    'grid-cols-[repeat(auto-fill,minmax(' + width + ',1fr))]'
].join(' ')
</script>

<template>
    <section
        class="teaser-grid grid grid-cols-[repeat(auto-fill,minmax(150px,1fr))] w-full gap-4 overflow-scroll"
    >
        <slot/>
    </section>
</template>
