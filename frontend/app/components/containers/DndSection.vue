<script setup lang="ts">
const props = defineProps<{
    class?: string
}>();
</script>

<template>
    <section
        class="bg-black/50 rounded-xl p-4"
        :class="props.class"
    >
        <slot/>
    </section>
</template>
