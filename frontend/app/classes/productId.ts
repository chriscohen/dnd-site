import {type Company, type CompanyApiResponse, createCompany} from "~/classes/company";

export interface ProductIdApiResponse {
    id?: string
    origin?: CompanyApiResponse
    productId?: string
    url?: string
}

export type ProductIdState = {
    id?: string
    origin?: Company
    productId?: string
    url?: string
}

export const createProductId = (data?: ProductIdApiResponse) => {
    const state: ProductIdState = {
        id: data?.id,
        origin: createCompany(data?.origin),
        productId: data?.productId,
        url: data?.url
    }

    return {
        ...state
    }
}

export type ProductId = ReturnType<typeof createProductId>;
