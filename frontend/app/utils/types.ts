import type {ReferenceApiResponse} from "~/classes/reference";
import { GameEdition } from "./enums";

export type BottomNavItem = {
    anchor: string
    disabled?: boolean
    icon: string
    name: string
};

export type EditionTabData = {
    id: string;
    class: string,
    label: string;
    suffix?: string
    disabled: boolean,
}

export interface PaginatedApiResponse<T> {
    current_page: number,
    data: T | T[],
    first_page_url: string,
    from: number,
    last_page: number,
    last_page_url: string,
    next_page_url: string | null,
    path: string,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number
}



export interface IAttackType {
    id: string
    name: string
}


export interface IFeat {
    __typename: "Feat"
    id: string
    slug: string
    name: string
    editions: IFeatEdition[]
}

export interface IFeatEdition {
    __typename: "FeatEdition"
    id: string
    slug: string
    name: string
    description: string
    game_edition: string
    prerequisites: IPrerequisite[]
    references: ReferenceApiResponse[]
}

export interface IPrerequisite {
    __typename: "Prerequisite"
    id: string
    type: string
    values: IPrerequisiteValue[]
}

export interface IPrerequisiteValue {
    __typename: "PrerequisiteValue"
    id: string
    value: string
}

export interface GameEditionDataItem {
    name: string,
    shortName: string,
    exclude?: boolean
}

export const GameEditionData: Record<string, GameEditionDataItem> = {
    '0e': {
        name: 'Original Edition',
        shortName: '0th',
        exclude: true
    },
    '1e': {
        name: 'First Edition',
        shortName: '1st'
    },
    '2e': {
        name: 'Second Edition',
        shortName: '2nd'
    },
    '3e': {
        name: 'Third Edition',
        shortName: '3rd'
    },
    '3.5': {
        name: 'Third Edition, Revised',
        shortName: '3.5'
    },
    '4e': {
        name: 'Fourth Edition',
        shortName: '4th'
    },
    '5e': {
        name: 'Fifth Edition',
        shortName: '5th'
    },
    '5.5': {
        name: 'Fifth Edition, Revised',
        shortName: '5.5'
    }
}

export interface SearchResult {
    id: string
    name: string
    slug: string
    type: string
}
