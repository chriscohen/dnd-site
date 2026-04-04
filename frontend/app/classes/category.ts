import type {Media, MediaApiResponse} from "~/classes/media";

export interface CategoryApiResponse {
    id: string
    slug: string
    name: string
    entityType: string
    parent?: CategoryApiResponse
    image?: MediaApiResponse
}

export type CategoryState = {
    id?: string,
    slug?: string,
    name?: string,
    entityType?: string,
    parent?: CategoryState,
    image?: Media
}

export const createCategory = (data?: CategoryApiResponse) => {
    const state: CategoryState = {
        id: data?.id,
        slug: data?.slug,
        name: data?.name,
        entityType: data?.entityType,
        parent: data?.parent,
        image: data?.image
    }

    return {
        ...state
    }
}

export type Category = ReturnType<typeof createCategory>;
