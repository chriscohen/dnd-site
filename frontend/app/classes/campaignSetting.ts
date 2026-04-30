import type {MediaApiResponse} from "~/classes/media";
import {type Company, type CompanyApiResponse, createCompany} from "~/classes/company";

export interface CampaignSettingApiResponse {
    id?: string
    description?: string
    logo?: MediaApiResponse
    name?: string
    publisher?: CompanyApiResponse
    publicationType?: string
    slug?: string
    shortName?: string
    startYear?: number
}

export type CampaignSettingState = {
    id?: string,
    description?: string,
    logo?: MediaApiResponse,
    name?: string,
    publisher?: Company,
    publicationType?: string,
    slug?: string,
    shortName?: string,
    startYear?: number
}

export const createCampaignSetting = (data?: CampaignSettingApiResponse) => {
    const state: CampaignSettingState = {
        id: data?.id,
        description: data?.description,
        logo: data?.logo,
        name: data?.name,
        publisher: createCompany(data?.publisher),
        publicationType: data?.publicationType,
        slug: data?.slug,
        shortName: data?.shortName,
        startYear: data?.startYear
    }

    return {
        ...state
    }
}

export type CampaignSetting = ReturnType<typeof createCampaignSetting>;
