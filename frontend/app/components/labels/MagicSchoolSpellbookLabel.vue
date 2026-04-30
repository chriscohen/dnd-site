<script setup lang="ts">
import PopoverBase from "~/components/popovers/PopoverBase.vue";
import type {MagicSchool} from "~/classes/magic/magicSchool";

const props = defineProps<{
    classes?: string
    size: string,
    school?: MagicSchool
}>();

</script>

<template>
    <div v-if="!school" class="skeleton magic-school-label" :style="{ width: size, height: size }"/>

    <UPopover v-if="school" mode="hover" :open-delay="500">
        <img
            class="magic-school-label"
            :class="classes"
            :src="school.parent ? school?.parent?.image?.url : school?.image?.url"
            :alt="school?.name + ' magic school'"
            :style="{ width: size, height: size }"
        >

        <template #content>
            <PopoverBase>
                <h3>{{ school?.name }}</h3>
                <p>{{ school?.description }}</p>
            </PopoverBase>
        </template>
    </UPopover>
</template>

<style lang="scss" scoped>
@use '~/assets/css/default/colors';
@use '~/assets/css/default/variables';

div[spell='reka-popper-content-wrapper'] {
    border-radius: 2rem;

    > div {
        background: none !important;
        display: none;
    }
}

.magic-school-label {
    filter: invert(.5);
    transition: filter variables.$default-delay ease-in-out;

    &:hover {
        filter: invert(1);
    }
}
</style>
