<script setup lang="ts">
const props = defineProps<{
    type: string
}>();

let iconName: string;
let iconPath: string;

switch (props.type.toLowerCase()) {
    case 'print':
        iconName = 'book-open-text';
        break;
    case 'pdf':
        iconName = 'file-text';
        break;
    case 'roll20 vtt':
        iconPath = '/icons/roll20.webp';
        break;
    default:
        iconName = '';
}
</script>

<template>
    <span class="flex flex-row items-center">
        <UIcon v-if="iconName?.length > 0" :name="'lucide:' + iconName" class="mr-1"/>
        <NuxtImg v-if="iconPath?.length > 0" :src="iconPath" class="mr-1 w-4"/>
        <slot/>
    </span>
</template>
