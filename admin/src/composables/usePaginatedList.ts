import { ref, watch, onMounted, onUnmounted, type Ref } from 'vue';
import type { PaginatedResponse } from '@dnd5e/types';

export function usePaginatedList<T>(
    fetchFn: (page: number, q?: string) => Promise<PaginatedResponse<T>>,
    errorText: string,
) {
    const items = ref<T[]>([]) as Ref<T[]>;
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
            const response = await fetchFn(page, q);
            items.value = page === 1 ? response.data : [...items.value, ...response.data];
            currentPage.value = response.current_page;
            lastPage.value = response.last_page;
        } catch {
            errorMessage.value = errorText;
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

    onMounted(async () => {
        await loadPage(1);

        const observer = new IntersectionObserver((entries) => {
            if (entries[0]?.isIntersecting && !loadingMore.value && currentPage.value < lastPage.value) {
                const q = searchQuery.value.length >= 3 ? searchQuery.value : undefined;
                loadPage(currentPage.value + 1, q);
            }
        }, { rootMargin: '200px' });

        if (sentinel.value) {
            observer.observe(sentinel.value);
        }

        onUnmounted(() => {
            if (debounceTimer) clearTimeout(debounceTimer);
            observer.disconnect();
        });
    });

    return { items, loading, loadingMore, errorMessage, sentinel, searchQuery };
}
