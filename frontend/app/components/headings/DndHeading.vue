<script setup lang="ts">
const props = defineProps<{
    underlineColor?: string
    class?: string,
    loading?: boolean
    size?: string,
    underline?: boolean
}>();

const divClasses = [
    props.underline && 'border-b',
    props.class,
    props.underline ? (props.underlineColor ? 'border-' + props.underlineColor : 'border-gray-400') : null
].join(' ');

const staticClasses = 'font-eaves';
const variableClasses = [
    staticClasses
].join(' ');
</script>

<template>
    <header>
        <div class="flex flex-row gap-4 justify-between items-center" :class="divClasses">
            <component :is="'h' + size" :class="variableClasses">
                <template v-if="loading">
                    <USkeleton class="h-4 w-full"/>
                </template>
                <template v-else><slot/></template>
            </component>

            <slot name="controls"/>
        </div>

        <slot name="subtitle"/>
    </header>
</template>
