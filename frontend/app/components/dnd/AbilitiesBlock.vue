<script setup lang="ts">

import type {AbilityScore} from "~/classes/abilities/abilityScore";

const props = defineProps<{
    abilities?: Record<string, AbilityScore>
}>();
</script>

<template>
    <ul class="font-scaly text-lg flex flex-row justify-evenly w-full py-2 px-4">
        <li title="Strength">
            <span class="font-bold block text-center uppercase">str</span>
            <span>
                {{ abilities?.str?.value }}
                ({{ abilities?.str?.modifierLabel() }})
            </span>
        </li>
        <li title="Dexterity">
            <span class="font-bold block text-center uppercase">dex</span>
            <span>
                {{ abilities?.dex?.value }}
                ({{ abilities?.dex?.modifierLabel() }})
            </span>
        </li>
        <li title="Constitution">
            <span class="font-bold block text-center uppercase">con</span>
            <span>
                {{ abilities?.con?.value }}
                ({{ abilities?.con?.modifierLabel() }})
            </span>
        </li>
        <li title="Intelligence">
            <span class="font-bold block text-center uppercase">int</span>
            <span>
                {{ abilities?.int?.value }}
                ({{ abilities?.int?.modifierLabel() }})
            </span>
        </li>
        <li title="Wisdom">
            <span class="font-bold block text-center uppercase">wis</span>
            <span>
                {{ abilities?.wis?.value }}
                ({{ abilities?.wis?.modifierLabel() }})
            </span>
        </li>
        <li title="Charisma">
            <span class="font-bold block text-center uppercase">cha</span>
            <span>
                {{ abilities?.cha?.value }}
                ({{ abilities?.cha?.modifierLabel() }})
            </span>
        </li>
    </ul>
</template>

<style scoped lang="scss">

ul {
    display: flex;
    width: 100%;
    justify-content: space-evenly;
    padding: 0.5rem 1rem;

    > li {
        > span {
            display: block;
            text-align: center;
            width: 100%;
        }

    }
}
</style>
