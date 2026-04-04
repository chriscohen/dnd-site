import {createMedia, type Media, type MediaApiResponse} from "~/classes/media";

export interface MagicSchoolApiResponse {
    id?: string
    name?: string
    children?: MagicSchoolApiResponse[]
    color?: string
    description?: string
    image?: MediaApiResponse
    parent?: MagicSchoolApiResponse
    shortName?: string
}

export type MagicSchoolState = {
    id?: string
    name?: string
    children?: MagicSchoolState[]
    color?: string
    description?: string
    image?: Media
    parent?: MagicSchoolState
    shortName?: string
}

export const createMagicSchool = (data?: MagicSchoolApiResponse) => {
    const state: MagicSchoolState = {
        id: data?.id,
        name: data?.name,
        children: data?.children?.map(createMagicSchool),
        color: data?.color,
        description: data?.description,
        image: data?.image ? createMedia(data?.image) : undefined,
        parent: data?.parent ? createMagicSchool(data?.parent) : undefined,
        shortName: data?.shortName
    }

    const hasChildren = (state.children?.length ?? 0) > 0;

    return {
        ...state,

        hasChildren
    }
}

export type MagicSchool = ReturnType<typeof createMagicSchool>;
