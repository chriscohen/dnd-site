export interface TagApiResponse {
    id: string,
    name: string
}

export type TagState = {
    id?: string,
    name?: string
};

export const createTag = (data?: TagApiResponse) => {
    const state = {
        id: data?.id,
        name: data?.name
    }

    return {
        ...state
    }
};

export type Tag = ReturnType<typeof createTag>;
