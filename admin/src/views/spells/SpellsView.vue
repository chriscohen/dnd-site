<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Message from 'primevue/message';
import InputText from 'primevue/inputtext';
import { getSpells } from "dnd5e-api";
import type { SpellApiResponse } from "@dnd5e/types";

const router = useRouter();

const spells = ref<SpellApiResponse[]>([]);
const loading = ref(false);
const loadingMore = ref(false);
const errorMessage = ref<string | null>(null);
const currentPage = ref(0);
const lastPage = ref(1);
const sentinel = ref<HTMLElement | null>(null);
const searchQuery = ref('');

async function loadPage(page: number, q?: string): Promise<void> {
    page === 1 ? (loading.value = true) : (loadingMore.value = true);
    errorMessage.value = null;

    try {
        const response = await getSpells(page, q);
        spells.value = page === 1 ? response.data : [...spells.value, ...response.data];
        currentPage.value = response.current_page;
        lastPage.value = response.last_page;
    } catch {
        errorMessage.value = 'Could not load spells. Please try again.';
    } finally {
        loading.value = false;
        loadingMore.value = false;
    }
}

let debounceTimer: ReturnType<typeof setTimeout> | null = null;

watch(searchQuery, (value) => {
    if (debounceTimer) clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        if (value.length >= 3) {
            loadPage(1, value);
        } else if (value.length === 0) {
            loadPage(1);
        }
    }, 300);
});

let observer: IntersectionObserver | null = null;

onMounted(async () => {
    await loadPage(1);

    observer = new IntersectionObserver((entries) => {
        if (entries[0]?.isIntersecting && !loadingMore.value && currentPage.value < lastPage.value) {
            const q = searchQuery.value.length >= 3 ? searchQuery.value : undefined;
            loadPage(currentPage.value + 1, q);
        }
    }, { rootMargin: '200px' });

    if (sentinel.value) {
        observer.observe(sentinel.value);
    }
});

onUnmounted(() => {
    if (debounceTimer) clearTimeout(debounceTimer);
    observer?.disconnect();
});
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">People</h1>

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
                        @click="router.push({ name: 'person.edit', params: { slug: data.slug } })"
                    />
                </template>
            </Column>
        </DataTable>

        <div ref="sentinel" class="h-1" />
        <div v-if="loadingMore" class="py-4 text-center text-sm text-muted">Loading more…</div>
    </div>
</template>
