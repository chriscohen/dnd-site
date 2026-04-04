<script setup lang="ts">
import type {BottomNavItem} from "~/utils/types";

const props = defineProps<{
    class?: string,
    items: BottomNavItem[]
}>();
</script>

<template>
    <div
        id="bottom-navigation"
        class="bg-black/80 absolute bottom-0 left-0 right-0 flex px-4 py-2 border-t-2 border-red-700
            md:hidden"
        :class="props.class"
    >
        <ul class="flex flex-row justify-evenly text-4xl w-full">
            <li v-for="item in items" :key="item.name" class="w-32 block">
                <UTooltip :text="item.name" arrow :content="{side: 'top'}" :delay-duration="0">
                    <ULink
                        :to="'#' + item.anchor"
                        size="2xl"
                        class="block text-center pt-1"
                        :disabled="item.disabled"
                    >
                        <UIcon :name="'lucide:' + item.icon" :class="item.disabled ? 'text-gray-600' : ''"/>
                    </ULink>
                </UTooltip>
            </li>
        </ul>
    </div>
</template>
