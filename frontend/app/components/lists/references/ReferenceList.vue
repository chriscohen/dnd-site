<script setup lang="ts">
import ReferenceListItem from "~/components/lists/references/ReferenceListItem.vue";
import DndHeading from "~/components/headings/DndHeading.vue";
import type {Reference} from "~/classes/reference";

const props = defineProps<{
    references: Reference[],
    showTitle?: boolean
}>();
</script>

<template>
    <section v-if="references" class="reference-page">
        <DndHeading v-if="showTitle" size="3" class="">References</DndHeading>
        <ReferenceListItem
            v-for="reference in references"
            :key="reference.id"
            :reference="reference"
        />
    </section>
</template>

<style scoped lang="scss">

.reference-page {
    margin-top: 0.5rem;

    > h3 {
        text-align: center;
    }
}
</style>
