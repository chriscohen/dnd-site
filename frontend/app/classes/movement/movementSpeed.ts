export interface MovementSpeedApiResponse {
    canHover?: boolean
    type?: MovementType
    value?: number
}

export type MovementSpeedState = {
    canHover?: boolean,
    type?: MovementType,
    value?: number
};

export const createMovementSpeed = (data?: MovementSpeedApiResponse | number | undefined) => {
    const state: MovementSpeedState = {
        canHover: (data === undefined || typeof data === 'number') ? false : data?.canHover,
        type: (data === undefined || typeof data === 'number') ? undefined : data?.type,
        value: typeof data === 'number' ? data : data?.value,
    }

    return {
        ...state
    }
};

export type MovementSpeed = ReturnType<typeof createMovementSpeed>;
