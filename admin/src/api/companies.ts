import { apiRequest } from './client';

export type Company = {
    id: string;
    name: string;
    slug: string;
    logo: { url: string } | null;
    productUrl: string | null;
    shortName: string | null;
    website: string | null;
};

type PaginatedResponse<T> = {
    data: T[];
    current_page: number;
    last_page: number;
    total: number;
    per_page: number;
};

export async function getCompanies(): Promise<PaginatedResponse<Company>> {
    return apiRequest<PaginatedResponse<Company>>('/api/companies');
}
