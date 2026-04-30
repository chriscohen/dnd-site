<script setup lang="ts">
</script>

<template>
    <aside class="label">
        <slot/>
    </aside>
</template>

<style lang="scss" scoped>
@use '~/assets/css/default/mixins';
aside.label {
    padding: 0.25rem 0.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex-grow: 1;
}
</style>
