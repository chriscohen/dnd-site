<script setup lang="ts">
import type {MagicSchool} from "~/classes/magic/magicSchool";
import MagicSchoolImage from "~/components/magic-schools/MagicSchoolImage.vue";

const props = defineProps<{
    school: MagicSchool
}>();
</script>

<template>
    <li>
        <NuxtLink :to="'/magic-schools/' + school.id" class="flex flex-row gap-4">
            <MagicSchoolImage :school="school.id ?? ''" class="w-8 h-8"/>
            <span class="font-eaves text-xl">{{ school.name }}</span>
        </NuxtLink>
    </li>
</template>
