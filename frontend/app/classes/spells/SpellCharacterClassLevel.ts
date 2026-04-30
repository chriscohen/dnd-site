export interface SpellCharacterClassLevelApiResponse {
    level?: number
    name?: string
    slug?: string
    type?: string
}

export type SpellCharacterClassLevelState = {
    level?: number
    name?: string
    slug?: string
    type?: string
}

export const createSpellCharacterClassLevel = (data?: SpellCharacterClassLevelApiResponse) => {
    const state: SpellCharacterClassLevelState = {
        level: data?.level,
        name: data?.name,
        slug: data?.slug,
        type: data?.type
    }

    return {
        ...state
    }
}

export type SpellCharacterClassLevel = ReturnType<typeof createSpellCharacterClassLevel>;
