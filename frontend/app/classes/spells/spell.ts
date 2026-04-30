import {createMedia, type Media, type MediaApiResponse} from "~/classes/media";
import {createSpellEdition, type SpellEdition, type SpellEditionApiResponse} from "~/classes/spells/spellEdition";

export interface SpellApiResponse {
    id: string
    slug: string
    name: string
    editions: SpellEditionApiResponse[]
    image: MediaApiResponse
    lowestLevel: number
    rarity: string
    school: string
}

export type SpellState = {
    id?: string
    slug?: string
    name?: string
    editions?: SpellEdition[]
    image?: Media
    lowestLevel?: number
    rarity?: string
    school?: string
}

export const createSpell = (data?: SpellApiResponse) => {
    const state: SpellState = {
        id: data?.id,
        slug: data?.slug,
        name: data?.name,
        editions: data?.editions?.map(createSpellEdition),
        image: createMedia(data?.image),
        lowestLevel: data?.lowestLevel,
        rarity: data?.rarity,
        school: data?.school
    }

    return {
        ...state
    }
}

export type Spell = ReturnType<typeof createSpell>;
