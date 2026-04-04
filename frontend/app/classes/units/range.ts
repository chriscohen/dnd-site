export interface RangeApiResponse {
    id?: string,
    isSelf?: boolean
    isTouch?: boolean
    number?: number
    perLevel?: string
    string?: string
    unit?: string
}

export type RangeState = {
    id?: string,
    isSelf?: boolean,
    isTouch?: boolean,
    number?: number,
    perLevel?: string,
    string?: string,
    unit?: string
}

export const createRange = (data?: RangeApiResponse) => {
    const state: RangeState = {
        id: data?.id,
        isSelf: data?.isSelf,
        isTouch: data?.isTouch,
        number: data?.number,
        perLevel: data?.perLevel,
        string: data?.string,
        unit: data?.unit
    }

    return {
        ...state
    }
}

export type Range = ReturnType<typeof createRange>;
