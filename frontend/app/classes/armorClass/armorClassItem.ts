export interface ArmorClassItemApiResponse {
    id: string
    sourceType: ArmorClassSource,
    source?: string
    value: number
}

export type ArmorClassItemState = {
    id?: string
    sourceType?: ArmorClassSource
    source?: string
    value?: number
};

export const createArmorClassItem = (data?: ArmorClassItemApiResponse) => {
    const state: ArmorClassItemState = {
        id: data?.id,
        sourceType: data?.sourceType,
        source: data?.source,
        value: data?.value
    }

    return {
        ...state
    }
};

export type ArmorClassItem = ReturnType<typeof createArmorClassItem>;
