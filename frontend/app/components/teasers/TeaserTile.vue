<script setup lang="ts">
import type {Media} from "~/classes/media";
import MediaImage from "~/components/media/MediaImage.vue";
import {NuxtLink} from "#components";
import TeaserTitle from "~/components/teasers/TeaserTitle.vue";
import EditionStrip from "~/components/strips/EditionStrip.vue";

const props = defineProps<{
    edition?: string
    href?: string
    media?: Media
    name?: string
}>();
</script>

<template>
    <component
        :is="href ? NuxtLink : 'div'"
        :to="href"
        class="bg-black/50 flex flex-col items-center justify-center animate-fade shadow relative group rounded-xl"
    >
        <template v-if="media">
            <MediaImage
                v-if="media"
                :media="media"
                class="m-auto"
                transform="thumbnail"
            />
            <TeaserTitle :title="name" hide/>
        </template>

        <template v-else>
            <header class="mx-4 my-8 flex flex-col items-center text-muted group-hover:text-white transition:colors duration-200 ease-in-out">
                <UIcon name="i-dnd-book" class="text-8xl"/>
                <div class="text-center uppercase font-bold">{{ name }}</div>
            </header>
        </template>
        <slot/>
        <EditionStrip v-if="edition" :edition="edition"/>
    </component>
</template>
