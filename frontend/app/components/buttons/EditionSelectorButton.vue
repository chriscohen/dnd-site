<script setup lang="ts">
const emit = defineEmits(['editionsUpdated']);

const props = defineProps<{
    editionId: string
    text: string
    sup?: string
    bgClass: string
}>();

const persistedStore = usePersistedStore();

function clicked(e: MouseEvent) {
    persistedStore.toggle(props.editionId);
    setTimeout(() => {
        persistedStore.reload++;
    }, 1000);
}

function isActive(id: string): boolean {
    return (persistedStore.get(id)?.active || persistedStore.get('all').active) ?? false;
}
</script>

<template>
    <button
        class="border-stripe text-nowrap cursor-pointer text-lg font-[Modesto] min-w-[2rem] px-2"
        :class="[props.bgClass, 'text-default', isActive(editionId) ? 'active' : ''].join(' ')"
        @click="clicked"
    >
        {{props.text}}
        <sup v-if="props.sup !== null" class="text-[1.25rem]">{{props.sup}}</sup>
    </button>
</template>

<style scoped lang="scss">
@use '~/assets/css/default/borders';
@use '~/assets/css/default/colors';
@use '~/assets/css/default/variables';

.edition-button {
    transition: border variables.$default-delay ease-in-out;
    text-wrap: nowrap;
}
.edition-all {
    &.active {
        border-color: colors.$border-active;
    }
    &.hover {
        border-color: colors.$border-hover;
    }
}
.edition-1 {
    &.active {
        border-color: colors.$edition-1-active;
    }
    &:hover {
        border-color: colors.$edition-1-hover;
    }
}
.edition-2 {
    &.active {
        border-color: colors.$edition-2-active;
    }
    &:hover {
        border-color: colors.$edition-2-hover;
    }
}
.edition-3 {
    &.active {
        border-color: colors.$edition-3-active;
    }
    &:hover {
        border-color: colors.$edition-3-hover;
    }
}
.edition-4 {
    &.active {
        border-color: colors.$edition-4-active;
    }
    &:hover {
        border-color: colors.$edition-4-hover;
    }
}
.edition-5 {
    &.active {
        border-color: colors.$edition-5-active;
    }
    &:hover {
        border-color: colors.$edition-5-hover;
    }
}
.edition-2024 {
    &.active {
        border-color: colors.$edition-2024-active;
    }
    &:hover {
        border-color: colors.$edition-2024-hover;
    }
}

</style>
