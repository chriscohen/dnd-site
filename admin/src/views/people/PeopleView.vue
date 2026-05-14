<script setup lang="ts">
import { useRouter } from 'vue-router';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Message from 'primevue/message';
import { getPeople } from "dnd5e-api";
import type { PersonApiResponse } from "@dnd5e/types";
import { usePaginatedList } from '@/composables/usePaginatedList';

const router = useRouter();

const { items: people, loading, loadingMore, errorMessage, sentinel } =
    usePaginatedList<PersonApiResponse>(getPeople, 'Could not load people. Please try again.');
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

        <div ref="sentinel" class="h-1" />
        <div v-if="loadingMore" class="py-4 text-center text-sm text-muted">Loading more…</div>
    </div>
</template>
