export interface SourceContentsApiResponse {
    id: string,
    editionId?: string
    name: string,
    ordinal: string,
    source?: string,
    type: string,
    headers: { header: string }[]
}

export type SourceContentsState = {
    id?: string
    editionId?: string
    name?: string
    ordinal?: string
    source?: string
    type?: string
    headers?: { header: string }[]
}

export const createSourceContents = (data?: SourceContentsApiResponse) => {
    const state: SourceContentsState = {
        id: data?.id,
        editionId: data?.editionId,
        name: data?.name,
        ordinal: data?.ordinal,
        source: data?.source,
        type: data?.type,
        headers: data?.headers
    }

    const hasHeaders = () => (state.headers?.length ?? 0) > 0;

    return {
        ...state,

        hasHeaders
    }
}

export type SourceContents = ReturnType<typeof createSourceContents>;
