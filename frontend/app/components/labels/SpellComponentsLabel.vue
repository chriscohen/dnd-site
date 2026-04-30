<script setup lang="ts">
const props = defineProps<{
    class?: string
    components: string
}>();

const pieces = props.components.split(',');
</script>

<template>
    <UPopover v-if="pieces.includes('V')" mode="hover" :content="{ side: 'top' }" arrow>
        <span class="underline">V</span>

        <template #content>
            <h5>Verbal</h5>
            <p>
                You must speak specific words, chants, or pitches to activate the magic, meaning you can't cast them while
                silenced or gagged.
            </p>
        </template>
    </UPopover>

    <UPopover v-if="pieces.includes('S')" mode="hover" :content="{ side: 'top' }" arrow>
        <span class="underline">S</span>

        <template #content>
            <h5>Somatic</h5>
            <p>
                You need to make precise hand movements or gestures, requiring at least one free hand, even if holding a
                two-handed weapon.
            </p>
        </template>
    </UPopover>

    <UPopover v-if="pieces.includes('M')" mode="hover" :content="{ side: 'top' }" arrow>
        <span class="underline">M</span>

        <template #content>
            <h5>Material</h5>
            <p>
                Involves using physical substances or objects, which can range from common items to expensive gems.
                Depending on the magic, items might be consumed on casting.
            </p>
        </template>
    </UPopover>

    <UPopover v-if="pieces.includes('DF')" mode="hover" :content="{ side: 'top' }" arrow>
        <span class="underline">DF</span>
    </UPopover>
</template>
