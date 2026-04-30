<script setup lang="ts">

import type {Category} from "~/classes/category";

const props = defineProps<{
    category: Category
    color?: string
}>();
</script>

<template>
    <a
        :href="'/category/' + category.slug"
        class="category-badge badge rounded-full text-sm"
    >
        {{ category.name }}
    </a>
</template>
