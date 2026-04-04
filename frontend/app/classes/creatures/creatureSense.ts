export interface CreatureSenseApiResponse {
    id: string
    description?: string,
    range?: number
    type: SenseType
    unit?: DistanceUnit
}

export type CreatureSenseState = {
    id?: string,
    description?: string,
    range?: number,
    type?: SenseType,
    unit?: DistanceUnit
}

export const createCreatureSense = (data?: CreatureSenseApiResponse) => {
    const state: CreatureSenseState = {
        id: data?.id,
        description: data?.description,
        range: data?.range,
        type: data?.type,
        unit: data?.unit
    }

    return {
        ...state
    }
};

export type CreatureSense = ReturnType<typeof createCreatureSense>;
