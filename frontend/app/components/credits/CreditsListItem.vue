<script setup lang="ts">
import EditionStrip from "~/components/strips/EditionStrip.vue";
import type {BookCredit} from "~/classes/sources/bookCredit";

const props = defineProps<{
    class?: string
    item: BookCredit
}>();
</script>

<template>
    <ULink
        :to="'/sources/' + item.source?.slug"
        class="flex flex-row gap-4 items-center hover:bg-highlight hover:text-gray-900 transition-colors
            duration-200 ease-in-out p-2"
        :class="props.class"
    >
        <EditionStrip vertical :edition="item.source?.gameEdition ?? '5e'"/>
        <NuxtImg
            v-if="item.source?.coverImage"
            :src="item.source?.coverImage.url"
            :alt="item.source?.name + ' cover image'"
            class="h-12"
        />
        <span class="text-base md:text-lg">{{ item.source?.name }}</span>
    </ULink>
</template>
