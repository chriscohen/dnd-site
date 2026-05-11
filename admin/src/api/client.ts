const apiBaseUrl = import.meta.env.VITE_API_BASE_URL as string;

export type PaginatedResponse<T> = {
    data: T[];
    current_page: number;
    last_page: number;
    total: number;
    per_page: number;
};

type ApiRequestOptions = RequestInit & {
    json?: unknown;
};

function getCookie(name: string): string | null {
    const cookies = document.cookie.split('; ');

    for (const cookie of cookies) {
        const [cookieName, ...cookieValueParts] = cookie.split('=');

        if (cookieName === name) {
            return decodeURIComponent(cookieValueParts.join('='));
        }
    }

    return null;
}

function shouldSendCsrfToken(method?: string): boolean {
    const normalizedMethod = method?.toUpperCase() ?? 'GET';

    return !['GET', 'HEAD', 'OPTIONS'].includes(normalizedMethod);
}

export class ApiError extends Error {
    public readonly status: number;
    public readonly response: Response;

    public constructor(message: string, status: number, response: Response) {
        super(message);

        this.name = 'ApiError';
        this.status = status;
        this.response = response;
    }
}

export async function apiRequest<T>(
    path: string,
    options: ApiRequestOptions = {}
): Promise<T> {
    const headers = new Headers(options.headers);

    headers.set('Accept', 'application/json');

    if (shouldSendCsrfToken(options.method)) {
        const xsrfToken = getCookie('XSRF-TOKEN');
        if (xsrfToken) {
            headers.set('X-XSRF-TOKEN', xsrfToken);
        }
    }

    let body = options.body;

    if (options.json !== undefined) {
        headers.set('Content-Type', 'application/json');
        body = JSON.stringify(options.json);
    }

    const response = await fetch(`${apiBaseUrl}${path}`, {
        ...options,
        headers,
        body,
        credentials: 'include'
    });

    if (!response.ok) {
        let message = `Request failed with status ${response.status}`;

        try {
            const data = await response.json() as { message?: string };

            if (data.message) {
                message = data.message;
            }
        } catch {
            // Ignore non-JSON error responses.
        }

        throw new ApiError(message, response.status, response);
    }

    if (response.status === 204) {
        return undefined as T;
    }

    return await response.json() as T;
}

export async function getCsrfCookie(): Promise<void> {
    const response = await fetch(`${apiBaseUrl}/sanctum/csrf-cookie`, {
        credentials: 'include',
        headers: {
            Accept: 'application/json'
        }
    });

    if (!response.ok) {
        throw new ApiError('Failed to fetch CSRF cookie', response.status, response);
    }
}
