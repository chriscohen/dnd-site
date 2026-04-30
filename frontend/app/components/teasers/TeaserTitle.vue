<script setup lang="ts">
const props = defineProps<{
    hide?: boolean
    title?: string,
    subtitle?: string,
    rarity?: string
}>();

const classes = [
    props.hide ? 'invisible opacity-0 group-hover:visible group-hover:opacity-100' : null,
    props.rarity ? 'border-' + props.rarity : null,
]
</script>

<template>
    <header
        :class="classes"
        class="absolute bottom-0 left-0 w-full bg-black/80 flex flex-col gap-2 transition-opacity duration-500
            ease-in-out pb-2"
    >
        <h3 class="text-xl p-2 text-center">{{ title }}</h3>
        <span v-if="subtitle" class="subtitle block text-center">
            {{ subtitle }}
        </span>
    </header>
</template>
