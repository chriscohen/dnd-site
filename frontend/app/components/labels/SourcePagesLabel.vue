<script lang="ts" setup>
import LabelBase from "~/components/labels/LabelBase.vue";

const props = defineProps({
    pages: { type: Number, required: true },
})
</script>

<template>
    <LabelBase label-type="pages-label" color="gray" opacity="10">
        <span class="first-line font[mrs-eaves]">{{ props.pages }}</span>
        <span class="second-line">Pages</span>
    </LabelBase>
</template>

<style scoped lang="scss">

aside.label {
    @apply text-white;
}
.first-line {
    display: block;
    font-size: 3rem;
    text-align: center;
}
.second-line {
    text-transform: uppercase;
    text-align: center;
    font-size: 1.5rem;
}
</style>
