<script setup lang="ts">
import type {MagicSchool} from "~/classes/magic/magicSchool";
import MagicSchoolImage from "~/components/magic-schools/MagicSchoolImage.vue";
import BaseCard from "~/components/cards/BaseCard.vue";
import DndHeading from "~/components/headings/DndHeading.vue";
import BaseButton from "~/components/buttons/BaseButton.vue";
import PopoverBase from "~/components/popovers/PopoverBase.vue";

const props = defineProps<{
    item?: MagicSchool
}>();
</script>

<template>
    <BaseCard class="flex flex-col h-full">
        <DndHeading size="2" underline>
            {{ item?.name }}

            <template #controls>
                <MagicSchoolImage :school="item?.id ?? ''" class="w-8 h-8"/>
            </template>
        </DndHeading>

        <span v-if="item?.hasChildren" class="italic mt-4">
            <PopoverBase :content="item?.children?.map(child => child.name).join(', ')">
                {{ item.children?.length === 1 ? '1 Subschool' : item.children.length + ' Subschools' }}
            </PopoverBase>
        </span>

        <div class="prose mt-2 mb-2">
            {{ item?.description }}
        </div>
        <div class="flex flex-row justify-end mt-auto border-t border-red-800">
            <BaseButton :to="'/magic-schools/' + item?.id" icon="i-lucide-chevron-right" label="more" class="mt-4"/>
        </div>
    </BaseCard>
</template>
