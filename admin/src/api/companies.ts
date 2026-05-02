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

export async function getCompany(slug: string): Promise<Company> {
    return apiRequest<Company>(`/api/company/${slug}`);
}

export type UpdateCompanyData = {
    name: string;
    slug: string;
    shortName?: string | null;
    website?: string | null;
    productUrl?: string | null;
};

export async function updateCompany(slug: string, data: UpdateCompanyData): Promise<Company> {
    return apiRequest<Company>(`/api/company/${slug}`, {
        method: 'PUT',
        json: {
            name: data.name,
            slug: data.slug,
            short_name: data.shortName || null,
            website: data.website || null,
            product_url: data.productUrl || null,
        },
    });
}
