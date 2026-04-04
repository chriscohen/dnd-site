<script setup lang="ts">
import BaseCard from "~/components/cards/BaseCard.vue";
import ProseSkeleton from "~/components/loading/ProseSkeleton.vue";

const props = defineProps<{
    class?: string
    loading?: boolean
    markdown?: string
    prose?: string
    size?: string
}>();

const { render } = useMarkdown();
const mdProse = computed(() => render(props.markdown));

</script>

<template>
    <BaseCard :class="props.class">
        <ProseSkeleton v-if="loading"/>
        <div
            v-else
            :class="size ? 'text-' + size : ''"
            class="h-full overflow-y-scroll"
            v-html="markdown ? mdProse : prose || ''"
        />
    </BaseCard>
</template>
