import {
  type CharacterClassEditionApiResponse, type CharacterClassEditionState, createCharacterClassEdition
} from "~/classes/characterClasses/characterClassEdition";
import {createMedia, type Media, type MediaApiResponse} from "~/classes/media";

export interface CharacterClassApiResponse {
    id?: string
    editions?: CharacterClassEditionApiResponse[]
    image?: MediaApiResponse
    isPrestige?: boolean
    name?: string
    slug?: string
}

export type CharacterClassState = {
    id?: string,
    editions?: CharacterClassEditionState[],
    image?: Media,
    isPrestige?: boolean,
    name?: string,
    slug?: string
}

export const createCharacterClass = (data?: CharacterClassApiResponse) => {
    const state: CharacterClassState = {
        id: data?.id,
        editions: data?.editions?.map(createCharacterClassEdition),
        image: createMedia(data?.image),
        isPrestige: data?.isPrestige,
        name: data?.name,
        slug: data?.slug
    }

    return {
        ...state
    }
}

export type CharacterClass = ReturnType<typeof createCharacterClass>;
