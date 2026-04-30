import {createReference, type Reference, type ReferenceApiResponse} from "~/classes/reference";

export interface CharacterClassEditionApiResponse {
    id?: string
    gameEdition?: string
    isPrestige?: boolean
    references?: ReferenceApiResponse[]
}

export type CharacterClassEditionState = {
    id?: string
    gameEdition?: string
    isPrestige?: boolean
    references?: Reference[]
}

export const createCharacterClassEdition = (data?: CharacterClassEditionApiResponse) => {
    const state = {
        id: data?.id,
        gameEdition: data?.gameEdition,
        isPrestige: data?.isPrestige,
        references: data?.references?.map(createReference)
    }

    return {
        ...state
    }
}

export type CharacterClassEdition = ReturnType<typeof createCharacterClassEdition>;
