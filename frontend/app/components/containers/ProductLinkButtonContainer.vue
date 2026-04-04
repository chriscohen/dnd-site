<script setup lang="ts">
import ProductLinkButton from "~/components/buttons/ProductLinkButton.vue";
import type {Source} from "~/classes/sources/source";

const props = defineProps<{
    source: Source
}>();
</script>

<template>
    <aside class="product-link-button-container px-4 py-2 flex gap-4 justify-center">
        <ProductLinkButton
            v-for="productId in source.productIds"
            :key="productId.id"
            :product-id="productId"
        />
    </aside>
</template>
