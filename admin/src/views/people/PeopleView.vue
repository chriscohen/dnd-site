<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
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
const loadingMore = ref(false);
const errorMessage = ref<string | null>(null);
const currentPage = ref(0);
const lastPage = ref(1);
const sentinel = ref<HTMLElement | null>(null);

async function loadPage(page: number): Promise<void> {
    page === 1 ? (loading.value = true) : (loadingMore.value = true);
    errorMessage.value = null;

    try {
        const response = await getPeople(page);
        people.value = page === 1 ? response.data : [...people.value, ...response.data];
        currentPage.value = response.current_page;
        lastPage.value = response.last_page;
    } catch {
        errorMessage.value = 'Could not load people. Please try again.';
    } finally {
        loading.value = false;
        loadingMore.value = false;
    }
}

let observer: IntersectionObserver | null = null;

onMounted(async () => {
    await loadPage(1);

    observer = new IntersectionObserver((entries) => {
        if (entries[0]?.isIntersecting && !loadingMore.value && currentPage.value < lastPage.value) {
            loadPage(currentPage.value + 1);
        }
    }, { rootMargin: '200px' });

    if (sentinel.value) {
        observer.observe(sentinel.value);
    }
});

onUnmounted(() => {
    observer?.disconnect();
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

        <div ref="sentinel" class="h-1" />
        <div v-if="loadingMore" class="py-4 text-center text-sm text-muted">Loading more…</div>
    </div>
</template>
