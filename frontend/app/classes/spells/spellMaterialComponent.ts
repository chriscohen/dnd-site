export interface SpellMaterialComponentApiResponse {
    id: string
    consumption?: string
    description?: string
    isFocus: boolean
    isPlural: boolean
    itemEditionId?: string
    minimumValue?: number
    name: string
    quantity?: number
    quantityText?: string
    spellEditionId?: string
    string?: string
}

export type SpellMaterialComponentState = {
    id?: string,
    consumption?: string
    description?: string,
    isFocus?: boolean,
    isPlural?: boolean,
    itemEditionId?: string,
    minimumValue?: number,
    name?: string,
    quantity?: number,
    quantityText?: string,
    spellEditionId?: string,
    string?: string
}

export const createSpellMaterialComponent = (data?: SpellMaterialComponentApiResponse) => {
    const state: SpellMaterialComponentState = {
        id: data?.id,
        consumption: data?.consumption,
        description: data?.description,
        isFocus: data?.isFocus,
        isPlural: data?.isPlural,
        itemEditionId: data?.itemEditionId,
        minimumValue: data?.minimumValue,
        name: data?.name,
        quantity: data?.quantity,
        quantityText: data?.quantityText,
        spellEditionId: data?.spellEditionId,
        string: data?.string
    }

    return {
        ...state
    }
}

export type SpellMaterialComponent = ReturnType<typeof createSpellMaterialComponent>;
