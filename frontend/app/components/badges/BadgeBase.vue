<script setup lang="ts">
const props = defineProps<{
    bgColor?: string
    class?: string
    extraText?: string
    label?: string
    rounded?: boolean
    type?: string
}>();

const classes = [
    'badge',
    props.rounded ? 'rounded-md' : null,
    props.type ? props.type + '-badge' : null,
    props.bgColor ? 'bg-' + props.bgColor : null,
    props.class
];
</script>

<template>
    <UTooltip
        :disabled="!extraText"
        :text="extraText"
        :content="{ side: 'top' }"
        :ui="{
            content: 'bg-black text-gray-300'
        }"
    >
        <span
            :class="classes"
            class="text-sm px-2 uppercase text-nowrap cursor-pointer"
        >
            {{ label }}
        </span>
    </UTooltip>
</template>
