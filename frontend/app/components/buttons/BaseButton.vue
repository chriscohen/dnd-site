<script setup lang="ts">
const props = defineProps<{
    icon?: string
    label?: string
}>();
</script>

<template>
    <UButton
        variant="link"
        :label="label"
        class="font-modesto bg-red-800 hover:bg-red-500 text-gray-300 hover:text-white text-xl cursor-pointer uppercase
            transition-colors duration-500 ease-in-out"
    >
        <template v-if="icon" #trailing>
            <UIcon :name="'lucide-' + icon"/>>
        </template>
    </UButton>
</template>
