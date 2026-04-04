export interface AreaApiResponse {
    id?: string
    height?: number
    length?: number
    per_level?: number
    quantity?: number
    radius?: number
    string?: string
    type?: string
    width?: number
}

export type AreaState = {
    id?: string
    height?: number
    length?: number
    per_level?: number
    quantity?: number
    radius?: number
    string?: string
    type?: string
    width?: number
}

export const createArea = (data?: AreaApiResponse) => {
    const state: AreaState = {
        id: data?.id,
        height: data?.height,
        length: data?.length,
        per_level: data?.per_level,
        quantity: data?.quantity,
        radius: data?.radius,
        string: data?.string,
        type: data?.type,
        width: data?.width
    }

    return {
        ...state
    }
}

export type Area = ReturnType<typeof createArea>;
