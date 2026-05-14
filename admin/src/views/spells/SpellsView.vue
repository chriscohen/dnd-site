<script setup lang="ts">
import { useRouter } from 'vue-router';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Message from 'primevue/message';
import InputText from 'primevue/inputtext';
import { getSpells } from "dnd5e-api";
import type { SpellApiResponse } from "@dnd5e/types";
import { usePaginatedList } from '@/composables/usePaginatedList';

const router = useRouter();

const { items: spells, loading, loadingMore, errorMessage, sentinel, searchQuery } =
    usePaginatedList<SpellApiResponse>(
        getSpells,
        'Could not load spells. Please try again.'
    );
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Spells</h1>

        <Message v-if="errorMessage" severity="error" class="mb-4">
            {{ errorMessage }}
        </Message>

        <div class="mb-4">
            <InputText
                v-model="searchQuery"
                placeholder="Search spells…"
                class="w-full"
            />
        </div>

        <DataTable :value="spells" :loading="loading">
            <template #empty>
                <span class="text-muted">No spells found.</span>
            </template>
            <Column field="name" header="Name">
                <template #body="{ data }: { data: SpellApiResponse }">
                    <RouterLink :to="{ name: 'spell.edit', params: { slug: data.slug } }">
                        {{ data.name }}
                    </RouterLink>
                </template>
            </Column>
            <Column field="slug" header="Slug"/>
            <Column field="lowestLevel" header="Level" class="hidden sm:block"/>
            <Column header="">
                <template #body="{ data }: { data: SpellApiResponse }">
                    <Button
                        v-tooltip.top="'Edit'"
                        icon="pi pi-pencil"
                        size="small"
                        @click="router.push({ name: 'spell.edit', params: { slug: data.slug } })"
                    />
                </template>
            </Column>
        </DataTable>

        <div ref="sentinel" class="h-1" />
        <div v-if="loadingMore" class="py-4 text-center text-sm text-muted">Loading more…</div>
    </div>
</template>
