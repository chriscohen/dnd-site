import type {CreatureTypeEditionState} from "~/classes/creatures/creatureTypeEdition";

export interface AbilityScoreApiResponse {
    isProficient?: boolean,
    modifier?: number,
    type?: string,
    value?: number
}

export type AbilityScoreState = {
    isProficient?: boolean,
    modifier?: number,
    type?: string,
    value?: number
}

export type AbilityScoresState = {
    str?: AbilityScore,
    dex?: AbilityScore,
    con?: AbilityScore,
    int?: AbilityScore,
    wis?: AbilityScore,
    cha?: AbilityScore,
};

export const createAbilityScore = (data?: AbilityScoreApiResponse) => {
    const state: AbilityScoreState = {
        isProficient: data?.isProficient,
        modifier: data?.modifier,
        type: data?.type,
        value: data?.value
    }

    return {
        ...state,

        ...withAbilityScore(state)
    }
}

export const withAbilityScore = (state: AbilityScoreState) => ({
    modifierLabel: (): string => {
        const modifier = state.modifier ?? 0;
        return modifier < 0 ? modifier.toString() : `+${modifier}`;
    }
});

export type AbilityScore = ReturnType<typeof createAbilityScore>;
