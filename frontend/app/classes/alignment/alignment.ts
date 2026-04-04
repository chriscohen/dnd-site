export interface AlignmentApiResponse {
    alignment?: string
}

export type AlignmentState = {
    alignment?: string
}

export const createAlignment = (data?: AlignmentApiResponse) => {
    const state: AlignmentState = {
        alignment: data?.alignment
    }

    const shortName = () => state.alignment?.charAt(0);

    return {
        ...state,

        shortName
    }
}

export type Alignment = ReturnType<typeof createAlignment>;
