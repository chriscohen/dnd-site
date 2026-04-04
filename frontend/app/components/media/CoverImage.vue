<script lang="ts" setup>
import type {Source} from "~/classes/sources/source";

const props = defineProps<{
    source?: Source
}>();
</script>

<template>
    <div class="max-h-80 max-w-[256px]">
        <img class="media-cover-image max-h-80 max-w-[256px]" :src="source?.coverImage?.url" alt="cover image">
    </div>
</template>

<style scoped lang="scss">
@use '../../assets/css/default/mixins';

.media-cover-image {
    max-height: 20rem;
    width: 100%;
    margin: 0 auto;
    position: relative;
}
</style>
