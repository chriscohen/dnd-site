export interface MagicDomainApiResponse {
    id?: string
    name?: string
}

export type MagicDomainState = {
    id?: string
    name?: string
}

export const createMagicDomain = (data?: MagicDomainApiResponse) => {
    const state: MagicDomainState = {
        id: data?.id,
        name: data?.name
    }

    return {
        ...state
    }
}

export type MagicDomain = ReturnType<typeof createMagicDomain>;
