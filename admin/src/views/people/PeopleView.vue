<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { getPeople } from "dnd5e-api";
import type { PersonApiResponse } from "@dnd5e/types";

const router = useRouter();

const people = ref<PersonApiResponse[]>([]);
const loading = ref(false);
const errorMessage = ref<string | null>(null);

onMounted(async () => {
    loading.value = true;
    errorMessage.value = null;

    try {
        const response = await getPeople();
        people.value = response.data;
    } catch {
        errorMessage.value = 'Could not load people. Please try again.';
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">People</h1>

        <Message v-if="errorMessage" severity="error" class="mb-4">
            {{ errorMessage }}
        </Message>

        <DataTable :value="people" :loading="loading">
            <Column field="name" header="Name">
                <template #body="{ data }: { data: PersonApiResponse }">
                    <RouterLink :to="{ name: 'person.edit', params: { slug: data.slug } }">
                        {{ data.firstName }} {{ data.lastName }}
                    </RouterLink>
                </template>
            </Column>
            <Column field="slug" header="Slug" />
            <Column header="">
                <template #body="{ data }: { data: PersonApiResponse }">
                    <Button
                        v-tooltip.top="'Edit'"
                        icon="pi pi-pencil"
                        size="small"
                        @click="router.push({ name: 'person.edit', params: { slug: data.slug } })"
                    />
                </template>
            </Column>
        </DataTable>
    </div>
</template>
