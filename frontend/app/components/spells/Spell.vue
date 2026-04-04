<script setup lang="ts">
import ClassesLevelsList from "~/components/lists/ClassesLevelsList.vue";
import ReferenceList from "~/components/lists/references/ReferenceList.vue";
import SavingThrowLabel from "~/components/labels/SavingThrowLabel.vue";
import SpellComponentsLabel from "~/components/labels/SpellComponentsLabel.vue";
import type {Spell} from "~/classes/spells/spell";
import type {SpellEdition} from "~/classes/spells/spellEdition";

const props = defineProps<{
    spell?: Spell
    edition?: SpellEdition
}>();

const editionsWithSavingThrow = [
    '2e',
    '3e',
    '3.5'
];
</script>

<template>
    <div>
        <div v-if="edition" class="spellbook-upper flex flex-col-reverse sm:flex-row gap-4 mb-4 items-start">
            <div class="font-eaves grid grid-cols[repeat(2, minmax(0, 1fr))]">
                <span>Components</span>
                <div class="flex gap-2">
                    <SpellComponentsLabel v-if="edition.spellComponents" :components="edition.spellComponents"/>
                </div>

                <span>Range</span>
                <span>{{ edition.range?.string }}</span>

                <template v-if="edition.area?.string">
                    <span>Area</span>
                    <span>{{ edition.area?.string }}</span>
                </template>

                <span>Casting Time</span>
                <span>{{ edition.castingTime }}</span>

                <template v-if="editionsWithSavingThrow.includes(edition?.gameEdition ?? '')">
                    <span>Saving Throw</span>
                    <SavingThrowLabel :edition="edition"/>
                </template>
            </div>
            <ClassesLevelsList :data="edition.levels" />
        </div>

        <hr/>
        <div class="mt-2 mb-4" v-html="edition?.description ?? ''"/>
        <hr/>

        <div class="spellbook-lower">
            <ReferenceList :references="edition?.references ?? []" :show-title="true"/>
        </div>
    </div>
</template>
