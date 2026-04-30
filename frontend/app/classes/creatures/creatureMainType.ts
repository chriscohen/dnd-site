import {
    createCreatureMainTypeEdition,
    type CreatureMainTypeEdition,
    type CreatureMainTypeEditionApiResponse
} from "~/classes/creatures/creatureMainTypeEdition";

export interface CreatureMainTypeApiResponse {
    id?: string
    editions?: CreatureMainTypeEditionApiResponse[]
    name?: string
    plural?: string
    slug?: string
}

export type CreatureMainTypeState = {
    id?: string
    editions?: CreatureMainTypeEdition[]
    name?: string
    plural?: string
    slug?: string
}

export const createCreatureMainType = (data?: CreatureMainTypeApiResponse) => {
    const state: CreatureMainTypeState = {
        id: data?.id,
        editions: data?.editions?.map(createCreatureMainTypeEdition),
        name: data?.name,
        plural: data?.plural,
        slug: data?.slug
    }

    const description = (): string | undefined => state?.editions?.[0]?.description

    return {
        ...state,

        description
    }
}

export type CreatureMainType = ReturnType<typeof createCreatureMainType>;
