<script setup lang="ts">
defineProps<{
    subtitle: string
    title: string
}>();
</script>
<template>
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-wide text-primary-400">
            Dashboard
        </p>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-white">
            {{ title }}
        </h1>
        <p class="mt-3 max-w-2xl text-sm text-muted">
            {{ subtitle }}
        </p>
    </div>
</template>
