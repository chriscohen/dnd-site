import { apiRequest } from './client';
import type {PaginatedResponse, SourceApiResponse} from "@dnd-site/types";

export async function getSources(): Promise<PaginatedResponse<SourceApiResponse>> {
    return apiRequest<PaginatedResponse<SourceApiResponse>>('/api/sources');
}

export async function getSource(slug: string): Promise<SourceApiResponse> {
    return apiRequest<SourceApiResponse>(`/api/sources/${slug}`);
}
