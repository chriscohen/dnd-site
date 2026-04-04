<script setup lang="ts">
const props = defineProps<{
    to: string,
    size: string
}>();

const linkStyle = {
    width: props.size,
    height: props.size
};
</script>

<template>
    <NuxtLink
        :to="to"
        class="block back-button text-neutral-200 block hover:bg-gray-100 transition-colors delay-200 ease-in-out
            border-none rounded-full text-dark"
        :style="linkStyle"
    >
        <Icon name="lucide:chevron-left" :size="size" />
    </NuxtLink>
</template>
