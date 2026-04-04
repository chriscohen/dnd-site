<script setup lang="ts">
import BackButton from "~/components/buttons/BackButton.vue";
import type {DropdownMenuItem} from "#ui/components/DropdownMenu.vue";

const props = defineProps<{
    backTo?: string
    loading?: boolean
    switcherItems?: DropdownMenuItem[]
    title?: string
}>();

const switcherOpen = ref<boolean>(false);
</script>

<template>
    <section id="page-title" class="flex flex-row">
        <div
            class="flex shrink-0 max-w-full gap-4 justify-between items-center border-b-2 border-red-800 pl-4 bg-black/80"
        >
            <UFieldGroup v-if="backTo">
                <BackButton :to="backTo" size="3rem"/>
            </UFieldGroup>

            <div class="w-full mb-2">
                <h1
                    v-if="!loading"
                    class="font-[Modesto] text-3xl sm:text-4xl md:text-5xl lg:text-6xl mt-1 md:mt-2 overflow-hidden"
                >
                    {{ title }}
                </h1>
                <USkeleton v-else class="h-16 md:h-24 w-lg"/>

                <h3 v-if="$slots.subtitle" class="text-sm sm:text-md lg:text-xl font-[mrs eaves] uppercase">
                    <slot name="subtitle"/>
                </h3>
            </div>

            <div v-if="switcherItems">
                <UDropdownMenu
                    :items="switcherItems"
                    :ui="{
                        content: 'bg-black/80',
                        item: 'font-eaves text-2xl hover:bg-gray-300 hover:text-gray-900'
                    }"
                >
                    <UButton
                        variant="ghost"
                        icon="i-lucide-chevron-down"
                        class="inline-block ml-2 text-4xl text-white hover:text-gray-950 hover:bg-gray-300"
                        @click="switcherOpen = !switcherOpen"
                    />
                </UDropdownMenu>
            </div>

            <div class="hidden sm:block">
                <slot name="labels"/>
            </div>
        </div>

        <!-- Fading background on right of title -->
        <div
            class="flex-1 min-w-0 w-64 h-full bg-linear-to-r from-black/80 to-black/0 border-b-2
                border-red-800"
        />

        <slot name="side"/>
    </section>
</template>
