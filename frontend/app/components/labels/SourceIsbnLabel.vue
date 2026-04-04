<script setup lang="ts">
import LabelBase from "~/components/labels/LabelBase.vue";

const props = defineProps<{
    label: string,
    isbn: string
}>();
</script>

<template>
    <LabelBase label-type="isbn-label">
        <span class="block">{{ label }}</span>
        <span class="block">{{ isbn }}</span>
    </LabelBase>
</template>

<style scoped lang="scss">
@use '~/assets/css/default/colors';

aside.label {
    background-color: colors.$gray-50-50;
    color: colors.$text-dark;
}
</style>
