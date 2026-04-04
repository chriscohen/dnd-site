import { useDebounceFn } from '@vueuse/core';
import type {SearchResult} from "~/utils/types";

export const useSearchStore = defineStore('search', () => {
    const query: Ref<string> = ref('');
    const results: Ref<SearchResult[]> = ref<SearchResult[]>([]);
    const isLoading: Ref<boolean> = ref(false);

    async function performSearch() {
        // Don't try to search unless we have a query of at least 3 characters.
        if (query.value.length < 3) {
            results.value = [];
            return;
        }

        isLoading.value = true;

        try {
            const { data } = await useFetch(useRuntimeConfig().public.apiUrl + '/search', {
                params: { q: query.value }
            });
            results.value = data.value as SearchResult[];
        } finally {
            isLoading.value = false;
        }
    }

    const debouncedSearch = useDebounceFn(performSearch, 300);

    watch (query, () => {
        debouncedSearch();
    });

    return {
        isLoading,
        query,
        results,
    }
});
