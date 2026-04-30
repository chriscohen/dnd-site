<script setup lang="ts">
import ProductListItem from "~/components/lists/ProductListItem.vue";
import DndHeading from "~/components/headings/DndHeading.vue";
import type {Source} from "~/classes/sources/source";
import BaseCard from "~/components/cards/BaseCard.vue";

const props = defineProps<{
    class?: string
    headingSize?: string
    loading?: boolean
    products?: Source[]
    title?: string
}>();

const productListRef = ref<HTMLElement | null>(null);
</script>

<template>
    <BaseCard :class="props.class" class="self-start flex flex-col">
        <DndHeading
            underline
            :size="headingSize ?? '2'"
            underline-color="red-800"
            class="mb-2"
        >
            {{ title ?? 'Products' }}
        </DndHeading>

        <template v-if="loading">
            <USkeleton class="w-full h-12"/>
        </template>

        <template v-else>
            <div v-if="products" class="overflow-y-scroll flex-1 min-h-0 mb-4">
                <span v-if="products.length === 0">No products found.</span>

                <ProductListItem v-for="product in products" :key="product.id" :product="product"/>
                <div ref="productListRef"/>
            </div>
        </template>
    </BaseCard>
</template>
