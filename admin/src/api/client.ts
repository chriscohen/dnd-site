const apiBaseUrl = import.meta.env.VITE_API_BASE_URL as string;

type ApiRequestOptions = RequestInit & {
    json?: unknown;
};

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
    options: ApiRequestOptions = {},
): Promise<T> {
    const headers = new Headers(options.headers);

    headers.set('Accept', 'application/json');
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
    await fetch(`${apiBaseUrl}/sanctum/csrf-cookie`, {
        credentials: 'include',
        headers: {
            Accept: 'application/json'
        }
    });
}
