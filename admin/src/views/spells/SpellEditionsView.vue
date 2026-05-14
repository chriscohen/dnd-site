<script setup lang="ts">
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Message from 'primevue/message';
import type { SpellEditionApiResponse } from "@dnd5e/types";
import { getSpellEditions } from "dnd5e-api";
import { usePaginatedList } from '@/composables/usePaginatedList';

const { items: spellEditions, loading, loadingMore, errorMessage, sentinel } =
    usePaginatedList<SpellEditionApiResponse>(
        getSpellEditions,
        'Could not load spell editions. Please try again.'
    );
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Spell Editions</h1>

        <Message v-if="errorMessage" severity="error" class="mb-4">
            {{ errorMessage }}
        </Message>

        <DataTable :value="spellEditions" :loading="loading">
            <template #empty>
                <span class="text-muted">No spell editions found.</span>
            </template>
            <Column field="spellSlug" header="Spell"/>
            <Column field="gameEdition" header="Game Edition"/>
        </DataTable>

        <div ref="sentinel" class="h-1"/>
        <div v-if="loadingMore" class="py-4 text-center text-sm text-muted">Loading more…</div>
    </div>
</template>
