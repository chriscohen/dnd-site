export interface ISavingThrow {
    id?: string
    failStatus?: string
    multiplier?: string
    type?: string
}

export type SavingThrowState = {
    id?: string
    failStatus?: string
    multiplier?: string
    type?: string
}

export const createSavingThrow = (data?: ISavingThrow) => {
    const state: SavingThrowState = {
        id: data?.id,
        failStatus: data?.failStatus,
        multiplier: data?.multiplier,
        type: data?.type
    }

    return {
        ...state
    }
}

export type SavingThrow = ReturnType<typeof createSavingThrow>;
