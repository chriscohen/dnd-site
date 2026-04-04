<script setup lang="ts">
const props = defineProps<{
    class?: string
    cr: number
}>();

let label;

switch (props.cr) {
    case 0.125:
        label = '<sup>1</sup>/<sub>8</sub>'; break;
    case 0.25:
        label = '<sup>1</sup>/<sub>4</sub>'; break;
    case 0.5:
        label = '<sup>1</sup>/<sub>2</sub>'; break;
    default:
        label = props.cr;
}
</script>

<template>
    <UTooltip
        arrow
        text="Challenge Rating"
        :delay-duration="0.25"
        :class="props.class"
    >
        <span v-html="'CR ' + label"/>
    </UTooltip>
</template>
