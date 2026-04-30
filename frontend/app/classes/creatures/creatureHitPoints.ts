export interface CreatureHitPointsApiResponse {
    id: string
    average?: number
    description?: string
    formula?: string
}

export type CreatureHitPointsState = {
    id?: string
    average?: number
    description?: string
    formula?: string
};

export const createCreatureHitPoints = (data?: CreatureHitPointsApiResponse) => {
    const state: CreatureHitPointsState = {
        id: data?.id,
        average: data?.average,
        description: data?.description,
        formula: data?.formula
    }

    return {
        ...state
    }
};

export type CreatureHitPoints = ReturnType<typeof createCreatureHitPoints>;
