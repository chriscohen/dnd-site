<script setup lang="ts">
import LabelBase from "~/components/labels/LabelBase.vue";

const props = defineProps({
    binding: { type: String, required: true },
});
</script>

<template>
    <LabelBase label-type="book-binding" color="blue">
        <span class="label-binding uppercase block text-center">{{ props.binding }}</span>
    </LabelBase>
</template>

<style scoped lang="scss">
@use '~/assets/css/default/colors';

aside.label {
    background-color: colors.$amber-700-20;

    > span {
        display: block;
        text-align: center;
        text-transform: uppercase;
    }
}
</style>
