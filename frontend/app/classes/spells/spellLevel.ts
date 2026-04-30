export interface SpellLevelApiResponse {
    name: string
    level: number
}

export type SpellLevelState = {
    name?: string
    level?: number
}

export const createSpellLevel = (data?: SpellLevelApiResponse) => {
    const state: SpellLevelState = {
        name: data?.name,
        level: data?.level
    }

    return {
        ...state
    }
}

export type SpellLevel = ReturnType<typeof createSpellLevel>;
