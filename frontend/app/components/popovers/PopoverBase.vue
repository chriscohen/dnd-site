<script setup lang="ts">
import DndHeading from "~/components/headings/DndHeading.vue";

const props = defineProps<{
    content?: string
    loading?: boolean
    openDelay?: number
    side?: 'top' | 'right' | 'bottom' | 'left'
    title?: string
}>();

const emit = defineEmits(['popover-open']);
function onOpen (isOpen: boolean) {
    if (!isOpen) return;
    emit('popover-open');
}
</script>

<template>
    <UPopover
        arrow
        mode="hover"
        :content="{ side: side ?? 'top' }"
        :ui="{
            content: 'bg-black text-neutral text-base border-2 border-gray-400 pb-4 max-w-md sm:max-w-lg'
        }"
        :open-delay="openDelay ?? 500"
        @update:open="onOpen"
    >
        <span class="cursor-pointer link"><slot/></span>

        <template #content>
            <div v-if="loading">
                <UIcon name="i-lucide-loader-circle" class="animate-spin"/>
            </div>

            <template v-else-if="content">
                <DndHeading v-if="title" underline size="3" class="mb-2">{{ title }}</DndHeading>
                {{ content }}
            </template>

            <div v-else>No content</div>
        </template>
    </UPopover>
</template>
