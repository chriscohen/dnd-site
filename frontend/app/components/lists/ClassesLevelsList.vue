<script setup lang="ts">
import type {SpellCharacterClassLevel} from "~/classes/spells/SpellCharacterClassLevel";

const props = defineProps<{
    data?: SpellCharacterClassLevel[]
}>();
</script>

<template>
    <aside v-if="data" class="classes-levels-page font[mrs-eaves]">
        <template v-for="item in props.data" :key="item.name">
            <span>
                <NuxtLink :to="'/' + (item.type == 'feat' ? 'feats/' : 'classes/') + item.slug" class="link">
                    {{ item.name }}
                </NuxtLink>
            </span>
            <span>{{ item.level }}</span>
        </template>
    </aside>
</template>

<style scoped lang="scss">

.classes-levels-page {
    border-radius: 0.5rem;
    display: grid;
    font-size: 125%;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    padding: 0.5rem 1rem;

    > span {
        display: inline-block;

        &:nth-child(even) {
            text-align: right;
            font-weight: bold;
        }
    }
}
</style>
