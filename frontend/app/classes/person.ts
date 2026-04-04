export interface PersonApiResponse {
    id: string
    artstation?: string
    firstName: string
    initials?: string[]
    instagram?: string
    lastName: string
    middleNames?: string
    slug: string
    twitter?: string
    youtube?: string
}

export type PersonState = {
    id?: string
    artstation?: string
    firstName?: string
    initials?: string[]
    instagram?: string
    lastName?: string
    middleNames?: string
    slug?: string
    twitter?: string
    youtube?: string
}

export const createPerson = (data?: PersonApiResponse) => {
    const state: PersonState = {
        id: data?.id,
        artstation: data?.artstation,
        firstName: data?.firstName,
        initials: data?.initials,
        instagram: data?.instagram,
        lastName: data?.lastName,
        middleNames: data?.middleNames,
        slug: data?.slug,
        twitter: data?.twitter,
        youtube: data?.youtube
    }

    const name = [
        state.firstName,
        state.middleNames ?? state.initials?.join(' '),
        state.lastName
    ].filter(item => item !== undefined).join(' ');

    return {
        ...state,

        name
    }
}

export type Person = ReturnType<typeof createPerson>;
