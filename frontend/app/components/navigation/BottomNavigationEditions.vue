<script setup lang="ts">

import type {TabsItem} from "#ui/components/Tabs.vue";

const props = defineProps<{
    class?: string,
    tabData: TabsItem[]
}>();

</script>

<template>
    <div
        id="bottom-navigation"
        class="bg-black/80 absolute bottom-0 left-0 right-0 flex px-4 py-2 border-t-2 border-red-700
            md:hidden"
        :class="props.class"
    >
        <ul class="flex flex-row text-4xl w-full">
            <li v-for="item in tabData" :key="item.label" class="block flex-1">
                <UButton
                    :label="item?.label?.toUpperCase()"
                    size="xl"
                    class="block text-center pt-1 w-full"
                    :disabled="item.disabled"
                />
            </li>
        </ul>
    </div>
</template>
