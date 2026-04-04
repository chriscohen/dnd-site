import {createReference, type Reference, type ReferenceApiResponse} from "~/classes/reference";

export interface LanguageApiResponse {
    id: string
    slug: string
    name: string
    isExotic?: boolean
    references?: ReferenceApiResponse[]
    scriptName?: string
}

export type LanguageState = {
    id?: string
    name?: string
    isExotic?: boolean
    references?: Reference[]
    scriptName?: string
}

export const createLanguage = (data?: LanguageApiResponse) => {
    const state: LanguageState = {
        id: data?.id,
        name: data?.name,
        isExotic: data?.isExotic,
        references: data?.references?.map(createReference),
        scriptName: data?.scriptName
    }

    return {
        ...state
    }
}

export type Language = ReturnType<typeof createLanguage>;
