<script setup lang="ts">
import BaseCard from "~/components/cards/BaseCard.vue";
import TabBase from "~/components/tabs/TabBase.vue";
import type {TabsItem} from "#ui/components/Tabs.vue";

const props = defineProps<{
    class?: string
    tabs?: TabsItem[]
}>();

const activeTab = ref<string | null>(null);

const handleTabClick = (tabName: string) => activeTab.value = tabName;
</script>

<template>
    <div class="flex flex-col">
        <nav>
            <ul class="flex flex-row">
                <TabBase
                    v-for="tab in tabs"
                    :key="tab.label"
                    :name="tab.label ?? ''"
                    :active="activeTab === null ? tab.active : activeTab === tab.label"
                    :disabled="tab.disabled"
                    @click="handleTabClick"
                >
                    {{ tab.label }}
                </TabBase>
            </ul>
        </nav>
        <BaseCard :tabs="true" :class="props.class">
            <slot/>
        </BaseCard>
    </div>
</template>
