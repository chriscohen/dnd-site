export interface CreatureMainTypeEditionApiResponse {
    id?: string
    alternateName?: string
    description?: string
    gameEdition?: string
}

export type CreatureMainTypeEditionState = {
    id?: string
    alternateName?: string
    description?: string
    gameEdition?: string
}

export const createCreatureMainTypeEdition = (data?: CreatureMainTypeEditionApiResponse) => {
    const state: CreatureMainTypeEditionState = {
        id: data?.id,
        alternateName: data?.alternateName,
        description: data?.description,
        gameEdition: data?.gameEdition,
    }

    return {
        ...state
    }
}

export type CreatureMainTypeEdition = ReturnType<typeof createCreatureMainTypeEdition>;
