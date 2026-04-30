import {createSource, type Source, type SourceApiResponse} from "~/classes/sources/source";

export interface ItemEditionApiResponse {
    id: string
    itemId: string
    gameEdition: string
    description: string
    price: number
    quantity: number
    weight: number
    source: SourceApiResponse
}

export type ItemEditionState = {
    id?: string,
    itemId?: string,
    gameEdition?: string,
    description?: string,
    price?: number,
    quantity?: number,
    weight?: number,
    source?: Source
}

export const createItemEdition = (data?: ItemEditionApiResponse) => {
    const state: ItemEditionState = {
        id: data?.id,
        itemId: data?.itemId,
        gameEdition: data?.gameEdition,
        description: data?.description,
        price: data?.price,
        quantity: data?.quantity,
        weight: data?.weight,
        source: createSource(data?.source)
    }

    return {
        ...state
    }
}

export type ItemEdition = ReturnType<typeof createItemEdition>;
