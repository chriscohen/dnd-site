import {
    createMovementSpeed,
    type MovementSpeed,
    type MovementSpeedApiResponse
} from "~/classes/movement/movementSpeed";

export interface MovementSpeedsGroupApiResponse {
    burrow?: MovementSpeedApiResponse | number,
    climb?: MovementSpeedApiResponse | number,
    fly?: MovementSpeedApiResponse | number,
    swim?: MovementSpeedApiResponse | number,
    walk?: MovementSpeedApiResponse | number
}

export type MovementSpeedsGroupState = {
    burrow?: MovementSpeed,
    climb?: MovementSpeed,
    fly?: MovementSpeed,
    swim?: MovementSpeed,
    walk?: MovementSpeed
}

export const createMovementSpeedsGroup = (data?: MovementSpeedsGroupApiResponse) => {
    const state: MovementSpeedsGroupState = {
        burrow: createMovementSpeed(data?.burrow),
        climb: createMovementSpeed(data?.climb),
        fly: createMovementSpeed(data?.fly),
        swim: createMovementSpeed(data?.swim),
        walk: createMovementSpeed(data?.walk),
    }

    const toString = (): string => {
        const output: string[] = [];
        Object.entries(state).forEach(([key, value]) => value?.value && output.push(`${key} ${value.value}`));
        return output.join(', ');
    }

    const can = (type: MovementType) => state[type]?.value !== undefined && state[type]?.value > 0;

    return {
        ...state,

        can,
        toString,
    }
}

export type MovementSpeedsGroup = ReturnType<typeof createMovementSpeedsGroup>;
