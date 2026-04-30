<script setup lang="ts">
const props = defineProps<{
    active?: boolean
    class?: string
    disabled?: boolean
    name: string
}>();

const emit = defineEmits(['click']);

const classes = computed(() => {
    return [
        props.class ?? '',
        props.active ? 'bg-black/80' : '',
        props.disabled ? 'cursor-not-allowed text-gray-600 bg-black-800/50' : 'cursor-pointer'
    ].join(' ');
});

const handleClick = () => {
    if (props.disabled) return;
    emit('click', props.name);
}
</script>

<template>
    <li class="px-4 py-2 rounded-t-md" :class="classes" @click="handleClick">
        <slot/>
    </li>
</template>
