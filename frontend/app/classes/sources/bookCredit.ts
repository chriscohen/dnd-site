import {createPerson, type Person, type PersonApiResponse,} from "~/classes/person";
import {createSource, type SourceApiResponse, type SourceState} from "~/classes/sources/source";

export interface BookCreditApiResponse {
    id?: string
    person?: PersonApiResponse
    role?: string
    source?: SourceApiResponse
}

export type BookCreditState = {
    id?: string
    person?: Person
    role?: string
    source?: SourceState
}

export const createBookCredit = (data?: BookCreditApiResponse) => {
    const state: BookCreditState = {
        id: data?.id,
        person: data?.person ? createPerson(data.person) : undefined,
        role: data?.role,
        source: data?.source ? createSource(data.source) : undefined
    }

    return {
        ...state
    }
}

export type BookCredit = ReturnType<typeof createBookCredit>;
