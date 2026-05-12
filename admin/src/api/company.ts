import { apiRequest } from './client';
import { type Company, type UpdateCompanyData, type PaginatedResponse } from '@dnd-site/types';

export async function getCompanies(): Promise<PaginatedResponse<Company>> {
    return apiRequest<PaginatedResponse<Company>>('/api/companies');
}

export async function getCompany(slug: string): Promise<Company> {
    return apiRequest<Company>(`/api/company/${slug}`);
}

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
