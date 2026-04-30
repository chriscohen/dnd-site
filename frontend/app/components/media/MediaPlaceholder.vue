<script setup lang="ts">
const props = defineProps<{
    icon: string
}>();
</script>

<template>
    <div class="flex items-center justify-center aspect-square bg-black/50 rounded-xl">
        <UIcon :name="props.icon" class="text-8xl"/>
    </div>
</template>
