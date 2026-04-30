<script setup lang="ts">

const props = defineProps<{
    edition: string
    vertical?: boolean
}>();
const className: string = 'bg-' + editionToClass(props.edition);
const classNameActive: string = className + '-active';
</script>

<template>
    <aside v-if="!vertical" class="block mt-auto h-2 absolute bottom-0 left-0 w-full" :class="classNameActive"/>

        <div v-else class="top-0 left-1/2 w-2 h-12" :class="classNameActive"/>
</template>

<style lang="scss">
@use '~/assets/css/default/backgrounds';

.sideways {
    writing-mode: vertical-lr;
    transform: scale(-1, -1);
}
</style>
