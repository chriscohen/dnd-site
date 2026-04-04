<script setup lang="ts">
const props = defineProps<{
    color?: string
    icon?: string
    to?: string
}>();

const backgroundColor = props.color ? 'bg-' + props.color + '-700' : 'bg-gray-700';
</script>

<template>
    <span :class="backgroundColor" class="inline-flex items-center px-2 rounded-full text-sm">
        <UIcon v-if="icon" :name="'lucide-' + icon" class="mr-1"/>
        <slot/>
    </span>
</template>
