import type {Media, MediaApiResponse} from "~/classes/media";

export interface ReferenceApiResponse {
    id: string
    pageFrom: number
    pageTo?: number
    shortName?: string
    source: string
    slug: string
    image: MediaApiResponse
}

export type ReferenceState = {
    id?: string
    image?: Media
    pageFrom?: number
    pageTo?: number
    shortName?: string
    source?: string
    slug?: string
};

export const createReference = (data?: ReferenceApiResponse) => {
    const state: ReferenceState = {
        id: data?.id,
        image: data?.image,
        pageFrom: data?.pageFrom,
        pageTo: data?.pageTo,
        shortName: data?.shortName,
        source: data?.source,
        slug: data?.slug
    }

    return {
        ...state
    }
};

export type Reference = ReturnType<typeof createReference>;
