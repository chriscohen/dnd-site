<script setup lang="ts">

import type {ProductId} from "~/classes/productId";

const props = defineProps<{
    productId: ProductId
}>();

</script>

<template>
    <ULink v-if="props.productId.url" :to="props.productId.url">
        <NuxtImg
            :src="productId.origin?.logo?.url"
            :alt="productId.origin?.name + ' logo'"
            class="website-logo"
        />
    </ULink>
</template>

<style scoped lang="scss">
.website-logo {
    filter: brightness(75%);
    max-height: 2rem;
    transition: 250ms ease-in-out;

    &:hover {
        filter: brightness(100%);
    }
}
</style>
