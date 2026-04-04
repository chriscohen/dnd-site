<script setup lang="ts">
import { onClickOutside } from "@vueuse/core";
import {DATA_TYPES} from "~/utils/utils";

const store = useSearchStore();

// Local state.
const searchClickOutsideRef = ref(null);
const showResults = ref<boolean>(false);

function onInput(input: string) {
    store.query = input;
    showResults.value = true;
}

function onSelect(option: { slug: string; }) {
    navigateTo('/campaign-settings/' + option.slug);
}

onClickOutside(searchClickOutsideRef, () => {
    showResults.value = false;
});

function onFocus() {
    if (store.results.length > 0) showResults.value = true;
}

</script>

<template>
    <div ref="searchClickOutsideRef" class="relative max-w-md my-2">
        <UInput
            variant="none"
            class="w-full bg-gray-950 text-base border-b-2 border-b-red-800 [&_input]:text-base font-light"
            :model-value="store.query"
            icon="i-lucide-search"
            placeholder="Search anything..."
            autocomplete="off"
            :loading="store.isLoading"
            @update:model-value="onInput"
            @focus="onFocus"
        >
            <template v-if="store.query.length > 0" #trailing>
                <UButton
                    variant="link"
                    size="lg"
                    icon="i-lucide-x"
                    class="text-gray-300 hover:text-white cursor-pointer"
                    @click="store.query = ''"
                />
            </template>
        </UInput>

        <ul
            v-if="showResults && store.results?.length > 0"
            class="absolute bg-gray-950 block top-full right-0 max-w-lg mt-2 max-h-[75dvh] overflow-y-scroll
                shadow-md/50 z-125"
        >
            <li v-for="item in store.results" :key="item.id">
                <a
                    :href="DATA_TYPES[item.type]?.path + item.slug"
                    class="group block text-left px-4 py-2 hover:bg-highlight hover:text-gray-800 transition-colors
                        duration-200 ease-in-out rounded-lg min-w-sm"
                >
                    <span class="block text-lg text-nowrap">{{ item.name}}</span>
                    <span class="block font-eaves text-md font-normal text-gray-400 group-hover:text-gray-800 -mt-2">
                        {{ DATA_TYPES[item.type]?.name }}
                    </span>
                </a>
            </li>
        </ul>
    </div>
</template>
