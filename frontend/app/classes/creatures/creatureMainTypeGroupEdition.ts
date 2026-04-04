import {createReference, type Reference, type ReferenceApiResponse} from "~/classes/reference";
import type {CreatureMainTypeGroupApiResponse} from "~/classes/creatures/creatureMainTypeGroup";

export interface CreatureMainTypeGroupEditionApiResponse {
    id: string
    creatureMainTypeGroup: CreatureMainTypeGroupApiResponse
    gameEdition: string
    references?: ReferenceApiResponse[]
}

export type CreatureMainTypeGroupEditionState = {
    id?: string
    //creatureMainTypeGroup?:
    gameEdition?: string
    references?: Reference[]
};

export const createCreatureMainTypeGroupEdition = (data?: CreatureMainTypeGroupEditionApiResponse) => {
    const state: CreatureMainTypeGroupEditionState = {
        id: data?.id,
        gameEdition: data?.gameEdition,
        references: data?.references?.map(createReference)
    }

    return {
        ...state
    }
};

export type CreatureMainTypeGroupEdition = ReturnType<typeof createCreatureMainTypeGroupEdition>;
