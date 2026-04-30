<script setup lang="ts">
const props = defineProps<{
    active?: boolean
    id?: string
    label?: string
}>();
</script>

<template>
    <li
        class="flex hover:bg-gray-50 hover:text-dark group cursor-pointer h-12 items-center px-4 py-1 duration-200
            ease-in-out"
        :class="active ? 'bg-highlight text-dark' : ''"
    >
        <template v-if="label">{{ label }}</template>
        <slot/>
    </li>
</template>
