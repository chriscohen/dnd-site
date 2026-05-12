export interface MediaApiResponse {
    id?: string
    url?: string
    collection_name?: string
    name?: string
    filename?: string
    size?: number
    mime_type?: string
}

export type MediaState = {
    id?: string
    collection_name?: string
    mime_type?: string
    name?: string
    filename?: string
    size?: number
    url?: string
};

export const createMedia = (data?: MediaApiResponse) => {
    const state: MediaState = {
        id: data?.id,
        collection_name: data?.collection_name,
        mime_type: data?.mime_type,
        name: data?.name,
        filename: data?.filename,
        size: data?.size,
        url: data?.url
    }

    return {
        ...state
    }
}

export type Media = ReturnType<typeof createMedia>;
