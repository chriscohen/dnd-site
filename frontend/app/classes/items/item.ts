import {createItemEdition, type ItemEdition, type ItemEditionApiResponse} from "~/classes/items/itemEdition";
import {type Category, type CategoryApiResponse, createCategory} from "~/classes/category";
import {createMedia, type MediaApiResponse} from "~/classes/media";

export interface ItemApiResponse {
    id?: string
    category?: CategoryApiResponse
    editions?: ItemEditionApiResponse[]
    image?: MediaApiResponse
    name?: string
    slug?: string
}

export type ItemState = {
    id?: string
    category?: Category
    editions?: ItemEdition[]
    image?: MediaApiResponse
    name?: string
    slug?: string
}

export const createItem = (data?: ItemApiResponse) => {
    const state: ItemState = {
        id: data?.id,
        category: createCategory(data?.category),
        editions: data?.editions?.map(createItemEdition),
        image: createMedia(data?.image),
        name: data?.name,
        slug: data?.slug
    }

    return {
        ...state
    }
}

export type Item = ReturnType<typeof createItem>;
