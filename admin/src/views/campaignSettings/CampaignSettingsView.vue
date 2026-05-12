<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Message from 'primevue/message';
import type {CampaignSettingApiResponse, SourceApiResponse} from "@dnd5e/types";
import {getCampaignSettings} from "dnd5e-api";

const router = useRouter();

const campaignSettings = ref<CampaignSettingApiResponse[]>([]);
const loading = ref(false);
const errorMessage = ref<string | null>(null);

onMounted(async () => {
    loading.value = true;
    errorMessage.value = null;

    try {
        const response = await getCampaignSettings();
        campaignSettings.value = response.data;
    } catch {
        errorMessage.value = 'Could not load sources. Please try again.';
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Campaign Settings</h1>

        <Message v-if="errorMessage" severity="error" class="mb-4">
            {{ errorMessage }}
        </Message>

        <DataTable :value="campaignSettings" :loading="loading">
            <Column field="name" header="Name">
                <template #body="{ data }: { data: CampaignSettingApiResponse }">
                    <RouterLink :to="{ name: 'source.edit', params: { slug: data.slug } }">
                        {{ data.name }}
                    </RouterLink>
                </template>
            </Column>
            <Column field="slug" header="Slug" />
            <Column header="">
                <template #body="{ data }: { data: CampaignSettingApiResponse }">
                    <Button
                        v-tooltip.top="'Edit'"
                        icon="pi pi-pencil"
                        size="small"
                        @click="router.push({ name: 'campaign-setting.edit', params: { slug: data.slug } })"
                    />
                </template>
            </Column>
        </DataTable>
    </div>
</template>
