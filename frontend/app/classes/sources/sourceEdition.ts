import {createProductId, type ProductIdApiResponse, type ProductIdState} from "~/classes/productId";
import {type Company, type CompanyApiResponse, createCompany} from "~/classes/company";
import {createMedia, type Media, type MediaApiResponse} from "~/classes/media";

export interface SourceEditionApiResponse {
    id: string
    sourcebookId: string
    name: string
    binding?: string
    coverImage?: MediaApiResponse
    formats: string[]
    gameEdition?: string
    hasContents?: boolean
    hasCredits?: boolean
    isbn10?: string
    isbn13?: string
    isPrimary?: boolean
    pages?: number
    productCode?: string
    productIds?: ProductIdApiResponse[]
    publisher?: CompanyApiResponse
    releaseDate?: string
    releaseDateMonthOnly?: boolean
}

export type SourceEditionState = {
    id?: string
    sourcebookId?: string
    name?: string
    binding?: string
    coverImage?: Media
    formats?: string[]
    gameEdition?: string
    hasContents?: boolean
    hasCredits?: boolean
    isbn10?: string
    isbn13?: string
    isPrimary?: boolean
    pages?: number
    productCode?: string
    productIds?: ProductIdState[]
    publisher?: Company
    releaseDate?: string
    releaseDateMonthOnly?: boolean
}

export const createSourceEdition = (data?: SourceEditionApiResponse) => {
    const state: SourceEditionState = {
        id: data?.id,
        sourcebookId: data?.sourcebookId,
        name: data?.name,
        binding: data?.binding,
        coverImage: data?.coverImage ? createMedia(data?.coverImage) : undefined,
        formats: data?.formats,
        gameEdition: data?.gameEdition,
        hasContents: data?.hasContents,
        hasCredits: data?.hasCredits,
        isbn10: data?.isbn10,
        isbn13: data?.isbn13,
        isPrimary: data?.isPrimary,
        pages: data?.pages,
        productCode: data?.productCode,
        productIds: data?.productIds?.map(createProductId),
        publisher: createCompany(data?.publisher),
        releaseDate: data?.releaseDate,
        releaseDateMonthOnly: data?.releaseDateMonthOnly
    };

    return {
        ...state
    }
}

export type SourceEdition = ReturnType<typeof createSourceEdition>;
