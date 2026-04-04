<script setup lang="ts">
import type {MovementSpeedsGroup} from "~/classes/movement/movementSpeedsGroup";
import {MovementType} from "~/utils/enums";

const props = defineProps<{
    movementSpeeds?: MovementSpeedsGroup
}>();
</script>

<template>
    <aside class="[&>span:not(:last-child)]:after:content-[','] [&>span:not(:last-child)]:mr-1">
        <span v-if="movementSpeeds?.can(MovementType.BURROW)" class="inline-flex flex-row items-center">
            <UIcon name="i-dnd-burrowing"/>
            burrow {{ movementSpeeds?.burrow?.value }} ft.
        </span>
        <span v-if="movementSpeeds?.can(MovementType.CLIMB)" class="inline-flex flex-row items-center">
            <UIcon name="i-dnd-climbing"/>
            climb {{ movementSpeeds?.climb?.value }} ft.
        </span>
        <span v-if="movementSpeeds?.can(MovementType.FLY)" class="inline-flex flex-row items-center">
            <UIcon name="i-dnd-flying"/>
            fly {{ movementSpeeds?.fly?.value }} ft.
        </span>
        <span v-if="movementSpeeds?.can(MovementType.SWIM)" class="inline-flex flex-row items-center">
            <UIcon name="i-dnd-swimming"/>
            swim {{ movementSpeeds?.swim?.value }} ft.
        </span>
        <span v-if="movementSpeeds?.can(MovementType.WALK)" class="inline-flex flex-row items-center">
            <UIcon name="i-dnd-walking"/>
            walk {{ movementSpeeds?.walk?.value }} ft.
        </span>
    </aside>
</template>
