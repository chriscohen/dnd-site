import { defineStore } from 'pinia';

type FetchFn = () => Promise<{ data: unknown[] } | unknown[]>;

export const useApiOptionsStore = defineStore('apiOptions', () => {
    const cache = new Map<FetchFn, unknown[]>();
    const pending = new Map<FetchFn, Promise<unknown[]>>();

    async function getOrFetch(fetchFn: FetchFn): Promise<unknown[]> {
        if (cache.has(fetchFn)) {
            return cache.get(fetchFn)!;
        }

        // Deduplicate concurrent requests for the same fetch function.
        if (pending.has(fetchFn)) {
            return pending.get(fetchFn)!;
        }

        const promise = fetchFn().then((result) => {
            const items = Array.isArray(result) ? result : result.data;
            cache.set(fetchFn, items);
            pending.delete(fetchFn);
            return items;
        });

        pending.set(fetchFn, promise);
        return promise;
    }

    return { getOrFetch };
});
