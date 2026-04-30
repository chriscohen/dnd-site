<script setup lang="ts">
import {formatPages} from "#imports";
import type {Reference} from "~/classes/reference";

const props = defineProps<{
    reference: Reference
}>();
</script>

<template>
    <NuxtLink
        v-if="reference"
        :href="'/sources/' + reference.slug"
        class="flex items-center gap-4 bold py-1 pr-4 pl-2"
    >
        <img :src="reference.image?.url" :alt="reference.source + ' thumbnail'" class="h-12">
        <span>{{ reference.source }}</span>
        <span class="font-light ml-auto">{{ formatPages(reference.pageFrom, reference?.pageTo) }}</span>
    </NuxtLink>
</template>
