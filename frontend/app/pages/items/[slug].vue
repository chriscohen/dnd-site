<script setup lang="ts">
import {useItemCache} from "~/stores/Store";
import {createItem, type ItemApiResponse} from "~/classes/items/item";

const route = useRoute();
const store = useItemCache();
const data: ItemApiResponse = await store.get({ key: route.params.slug as string }) as ItemApiResponse;
const item = createItem(data);
</script>

<template>
    <div class="item">
        <!-- Left: Cover Art -->
        <img
            :src="item.image?.url"
            :alt="item.name + ' cover image'"
            class="media-large hover:scale-105 transition-all duration-500"
        >
        <!-- /Left: Cover Art -->

        <!-- Right Side -->
        <div>
            <!-- Heading -->
            <div class="flex items-end border-b border-gray-400 mb-4">
                <NuxtLink to="/items">
                    <Icon name="lucide:chevron-left" class="text-gray-500 hover:text-gray-200 hover:animate-bounce-left" size="3rem" />
                </NuxtLink>
                <h1 class="font-[Modesto_Condensed] text-6xl mr-8">{{ item?.name}}</h1>
            </div>
            <!-- /Heading -->

            <!-- Under Heading -->
            <div class="grid grid-cols-3 gap-16">
                <!-- Left of "Under Heading" -->
                details
                <!-- Left of "Under Heading" -->

                <!-- Right of "Under Heading -->
                <div v-if="false" class="col-span-2 markdown" v-html="item"/>
                <!-- Right of "Under Heading -->
            </div>
            <!-- /Under Heading -->
        </div>
        <!-- /Right Side -->
    </div>
</template>

<style scoped lang="scss">
.item {
    display: flex;
    gap: 4rem;
}
</style>
