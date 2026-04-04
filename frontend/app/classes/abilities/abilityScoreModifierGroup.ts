export interface AbilityScoreModifierGroupApiResponse {
    str?: number,
    dex?: number,
    con?: number,
    int?: number,
    wis?: number,
    cha?: number,
    choose?: {
        from: string[],
        amount?: number
        count?: number
    }
}

export type AbilityScoreModifierGroupState = {
    str?: number,
    dex?: number,
    con?: number,
    int?: number,
    wis?: number,
    cha?: number,
    choose?: {
        from: string[],
        amount?: number
    }
}
