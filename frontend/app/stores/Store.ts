import {defineStore, skipHydrate} from "pinia";
import {ref, type Ref} from "vue";
import type {LanguageApiResponse} from "~/classes/language";
import type {SourceApiResponse} from "~/classes/sources/source";
import type {SpellApiResponse} from "~/classes/spells/spell";
import type {CreatureTypeApiResponse} from "~/classes/creatures/creatureType";
import type {ItemApiResponse} from "~/classes/items/item";
import type {CompanyApiResponse} from "~/classes/company";
import type {CampaignSettingApiResponse} from "~/classes/campaignSetting";
import type {CharacterClass} from "~/classes/characterClasses/characterClass";
import type {CreatureMainTypeApiResponse} from "~/classes/creatures/creatureMainType";
import type {PersonApiResponse} from "~/classes/person";
import type {MagicSchoolApiResponse} from "~/classes/magic/magicSchool";

interface GetProps {
    disableSSR?: boolean
    key: string
    params?: Record<string, string>
    path?: string
}
interface ListProps {
    disableSSR?: boolean
    key?: string
    params?: Record<string, string>
    path?: string
}

/**
 *
 * @param storeId
 * @param useGetPath  Relative to the API base URL. Should contain {key} where the key of the item is to be replaced.
 * @param useListPath
 */
export function createCacheStore<T>(
    storeId: string,
    useGetPath: string,
    useListPath?: string
) {
    interface StoredApiResponse {
        currentPage?: number
        hasMore?: boolean
        lastUrl?: string
        nextUrl?: string | null
        type: 'get' | 'list' | 'pager'
        item?: T
        items?: T[]
    }

    return defineStore(storeId, () => {
        const runtime = useRuntimeConfig();
        const items: Ref<Record<string, StoredApiResponse>> = ref({});

        const pendingUrls = ref(new Set<string>());

        const empty = computed(() => Object.keys(items.value).length === 0);
        const isLoading = computed(() => pendingUrls.value.size > 0);
        const activePage = ref<string | undefined>();

        function clear() {
            items.value = {};
        }

        function getItem(key: string): T | undefined {
            return items.value?.[key]?.item;
        }

        function getItems(key?: string): T[] {
            const keyToUse = key ?? activePage.value ?? '';
            return hasUrl(keyToUse) ? (items.value?.[keyToUse]?.items ?? []) : [];
        }

        function getPage(key?: string): StoredApiResponse | undefined {
            const keyToUse = key ?? activePage.value ?? '';
            return items.value?.[keyToUse];
        }

        async function get(props: GetProps = { key: '' }): Promise<T | undefined> {
            if (props.disableSSR && !import.meta.client) return; // Force client-side only.
            const url = getUrl(props.path ?? useGetPath, props.key, props.params);

            // If we have it cached, return it immediately.
            if (hasUrl(url)) return items.value?.[url]?.item;

            // Don't allow a second request for an item we're already fetching.
            if (pendingUrls.value.has(url)) return undefined;
            pendingUrls.value.add(url);

            try {
                const data = await $fetch<T>(url);

                // Success - store and return.
                items.value[url] = {
                    type: 'get',
                    item: data
                };

                return items.value[url].item;
            } catch (error: any) {
                console.error(`Failed to fetch ${url}`, error.value);
                return undefined;
            } finally {
                // Make sure to always remove the URL from the pending page.
                pendingUrls.value.delete(url);
            }
        }

        /**
         * Call an API method that returns a list that is not paginated.
         */
        async function list(props: ListProps = {}): Promise<T[] | undefined> {
            if (props.disableSSR && !import.meta.client) return; // Force client-side only.
            const url = getUrl(props.path ?? useListPath ?? '', props.key, props.params);

            // Check if it's already cached.
            if (hasUrl(url)) return items.value?.[url]?.items;

            // Don't allow a second request for an item we're already fetching.
            if (pendingUrls.value.has(url)) return undefined;
            pendingUrls.value.add(url);

            try {
                const page = getPage(url) ?? { type: 'list', items: [] };
                const data = await $fetch<T[]>(url);
                if (!page.items) page.items = [];
                page.items.push(...data);
                items.value[url] = page;
                return page.items;
            } catch (error: any) {
                console.error(`Failed to fetch ${url}`, error.value);
                return undefined;
            } finally {
                pendingUrls.value.delete(url);
            }
        }

        /**
         * Call an API method that returns a PaginatedApiResponse.
         */
        async function page(props: ListProps = {}): Promise<T[] | undefined> {
            if (props.disableSSR && !import.meta.client) return; // Force client-side only.
            const url = getUrl(props.path ?? useListPath ?? '', props.key, props.params);

            // Is there another page available?
            const page = getPage(url) ?? {
                type: 'pager',
                currentPage: 0,
                hasMore: true,
                lastUrl: url,
                nextUrl: url,
                items: []
            };
            if (!page.hasMore) return page.items;

            // Don't allow a second request for an item we're already fetching.
            if (pendingUrls.value.has(url)) return;
            pendingUrls.value.add(url);

            // Do we already have an entry for this URL? If not, create one.
            if (page.currentPage) page.currentPage++; else page.currentPage = 1;

            // Set this as the active page.
            activePage.value = url;

            try {
                const data = await $fetch<PaginatedApiResponse<T>>(page.nextUrl ?? '');

                // Success - append data.
                if (!page.items) page.items = [];
                page.items.push(...(data.data as T[]));

                // Update the pointer URL to the next page.
                page.lastUrl = page.nextUrl ?? '';
                page.nextUrl = data.next_page_url;

                // If there's no next URL, we're done.
                if (page.nextUrl === null) page.hasMore = false;

                // Assign the page back into the store.
                items.value[url] = page;
                return page.items ?? [];
            } catch (error) {
                console.error(`Failed to fetch ${page.nextUrl}`, error);
                page.hasMore = false;
                items.value[url] = page;
            } finally {
                // Make sure to always remove the URL from the pending page.
                pendingUrls.value.delete(url);
            }
        }

        /**
         * Build a URL for the given path and query string parameters.
         *
         * @param path   The relative path containing a replacement key, for example, "creature/{key}"
         * @param key    The key to replace in the path.
         * @param params A map of query string parameters as key/value pairs.
         */
        function getUrl(path: string, key?: string, params: Record<string, string> = {}): string {
            const myPath = path.replace('{key}', key ?? '');
            return runtime.public.apiUrl + '/' + myPath + '?' + Object.entries(params).map(([key, value]) => {
                return key + '=' + value;
            }).join('&');
        }

        function hasUrl(url: string): boolean {
            return !!items.value[url];
        }

        return {
            activePage,
            empty,
            items,
            isLoading,
            // Don't allow the server to hydrate the pending URLs, because this could happen after the server initiated
            // a request, but before it received a response, leading to a "stuck" pendingUrl on the client with no
            // request being made.
            pendingUrls: skipHydrate(pendingUrls),

            // Functions for store management.
            hasUrl,
            clear,
            getItem,
            getItems,
            getPage,

            // Make API requests.
            get,
            list,
            page
        };
    });
}

export const useBookCreditCache = createCacheStore(
    'book-credit',
    'book-credit/{key}',
    'person/{key}/credits'
);
export const useCampaignSettingCache = createCacheStore<CampaignSettingApiResponse>(
    'campaign-setting',
    'campaign-setting/{key}',
    'campaign-settings'
);
export const useCharacterClassCache = createCacheStore<CharacterClass>(
    'character-class',
    'character-class/{key}',
    'character-classes'
);
export const useCompanyCache = createCacheStore<CompanyApiResponse>(
    'company',
    'company/{key}',
    'companies'
);
export const useCreatureTypeCache = createCacheStore<CreatureTypeApiResponse>(
    'creature-type',
    'creature-type/{key}',
    'creature-types'
);
export const useCreatureMainTypeCache = createCacheStore<CreatureMainTypeApiResponse>(
    'creature-main-type',
    'creature-main-type/{key}',
    'creature-main-types'
);
export const useItemCache = createCacheStore<ItemApiResponse>(
    'item',
    'item/{key}',
    'items'
);
export const useLanguageCache = createCacheStore<LanguageApiResponse>(
    'language',
    'language/{key}',
    'languages'
);
export const useMagicSchoolCache = createCacheStore<MagicSchoolApiResponse>(
    'magic-school',
    'magic-school/{key}',
    'magic-schools'
);
export const usePersonCache = createCacheStore<PersonApiResponse>(
    'person',
    'person/{key}',
    'people'
);
export const useSourceCache = createCacheStore<SourceApiResponse>(
    'source',
    'source/{key}',
    'sources'
);

export const useSourceContentsCache = createCacheStore(
    'source-contents',
    'source/{key}/contents'
);
export const useSpellCache = createCacheStore<SpellApiResponse>(
    'spell',
    'spell/{key}',
    'spells'
);
