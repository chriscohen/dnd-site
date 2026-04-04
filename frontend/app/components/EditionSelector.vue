<script setup lang="ts">
import EditionSelectorButton from "~/components/buttons/EditionSelectorButton.vue";

const persistedStore = usePersistedStore();
</script>

<template>
    <UFieldGroup class="hidden xl:flex">
        <EditionSelectorButton
            v-for="edition in persistedStore.editions"
            :key="edition.id"
            :edition-id="edition.id"
            :text="edition.text"
            :sup="edition.sup"
            :bg-class="edition.bgClass"
        />
    </UFieldGroup>
</template>
