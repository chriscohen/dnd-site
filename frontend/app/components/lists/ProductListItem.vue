<script setup lang="ts">
import EditionStrip from "~/components/strips/EditionStrip.vue";
import type {Source} from "~/classes/sources/source";

const props = defineProps<{
    class?: string
    product: Source
}>();
</script>

<template>
    <ULink
        :to="'/sources/' + product.slug"
        class="flex flex-row gap-2 items-center hover:bg-highlight hover:text-gray-900 transition-colors
            duration-200 ease-in-out p-2"
        :class="props.class"
    >
        <EditionStrip vertical :edition="product.gameEdition ?? '5e'"/>
        <NuxtImg
            v-if="product.coverImage"
            :src="product.coverImage.url"
            :alt="product.name + ' cover image'"
            class="h-12"
        />
        <span class="text-base ml-2 md:text-lg">{{ product.name }}</span>
    </ULink>
</template>
