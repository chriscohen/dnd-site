import {
    createCreatureMainType,
    type CreatureMainType,
    type CreatureMainTypeApiResponse
} from "~/classes/creatures/creatureMainType";

export interface CreatureMainTypeGroupApiResponse {
    id?: string
    gameEdition?: string
    mainType?: CreatureMainTypeApiResponse
    origin?: string
}

export type CreatureMainTypeGroupState = {
    id?: string
    gameEdition?: string,
    mainType?: CreatureMainType,
    origin?: string
}

export const createCreatureMainTypeGroup = (data?: CreatureMainTypeGroupApiResponse) => {
    const state: CreatureMainTypeGroupState = {
        id: data?.id,
        gameEdition: data?.gameEdition,
        mainType: createCreatureMainType(data?.mainType),
        origin: data?.origin
    }

    const toString = (): string | undefined => {
        return state?.mainType?.name;
    }

    return {
        ...state,

        toString
    }
};

export type CreatureMainTypeGroup = ReturnType<typeof createCreatureMainTypeGroup>;
