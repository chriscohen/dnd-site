import {
    createCreatureTypeEdition, type CreatureTypeEdition,
    type CreatureTypeEditionApiResponse
} from "~/classes/creatures/creatureTypeEdition";
import type {TabsItem} from "#ui/components/Tabs.vue";

export interface CreatureTypeApiResponse {
    id?: string,
    name?: string,
    slug?: string,
    children?: CreatureTypeApiResponse[],
    editions?: CreatureTypeEditionApiResponse[],
    parent?: CreatureTypeApiResponse,
}

export type CreatureTypeState = {
    id?: string
    name?: string
    slug?: string
    editions?: CreatureTypeEdition[]
}

export const createCreatureType = (data?: CreatureTypeApiResponse) => {
    const state: CreatureTypeState = {
        id: data?.id,
        name: data?.name,
        slug: data?.slug,
        editions: data?.editions?.map(createCreatureTypeEdition)
    }

    const defaultEdition = (): CreatureTypeEdition | undefined => state.editions?.[0];

    const hasEditions = (): boolean => (state.editions?.length ?? 0) > 0;

    const sizeAndType = (): string | undefined => {
        const edition = defaultEdition();

        if (!edition) return;
        return edition.getSizes() + ' ' + edition.getType();
    };

    const toTabs = (): TabsItem[] => {
        const tabs: TabsItem[] = [];

        Object.entries(GameEditionData).forEach(([key, data]) => {
            if (data.exclude) return;

            const tab: TabsItem = {
                label: data.shortName,
            };

            // Do we have this edition for our creatureType?
            const item = state.editions?.find((edition: CreatureTypeEdition) => edition.gameEdition === key);
            tab.disabled = !item;

            tabs.push(tab);
        });

        // Set the most recent edition that exists, as active.
        let mostRecentDone = false;

        tabs.reverse().forEach((tab) => {
            if (!mostRecentDone && !tab.disabled) {
                tab.active = true;
                mostRecentDone = true;
            }
        });

        return tabs;
    }

    return {
        ...state,

        defaultEdition,
        hasEditions,
        sizeAndType,
        toTabs,
    }
}

export type CreatureType = ReturnType<typeof createCreatureType>;
