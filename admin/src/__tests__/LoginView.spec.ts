import { describe, it, expect, vi, beforeEach } from 'vitest'
import { mount, flushPromises } from '@vue/test-utils'
import { createPinia } from 'pinia'
import PrimeVue from 'primevue/config'
import LoginView from '../views/LoginView.vue'
import { ApiError } from '@/api/client'

const mockPush = vi.fn()
vi.mock('vue-router', () => ({
    useRoute: () => ({ query: {} }),
    useRouter: () => ({ push: mockPush }),
}))

const mockLogin = vi.fn()
vi.mock('@/stores/auth', () => ({
    useAuthStore: () => ({
        login: mockLogin,
        isLoggingIn: false,
    }),
}))

function mountView() {
    return mount(LoginView, {
        global: {
            plugins: [createPinia(), PrimeVue],
        },
    })
}

function mockResponse(status: number): Response {
    return new Response(null, { status })
}

async function submitForm(wrapper: ReturnType<typeof mountView>) {
    await wrapper.find('form').trigger('submit')
    await flushPromises()
}

describe('LoginView — validation', () => {
    beforeEach(() => {
        vi.clearAllMocks()
    })

    it('shows "Email is required" when email is empty on submit', async () => {
        const wrapper = mountView()
        await submitForm(wrapper)
        expect(wrapper.text()).toContain('Email is required')
    })

    it('shows "Enter a valid email address" for a malformed email', async () => {
        const wrapper = mountView()
        await wrapper.find('input[type="email"]').setValue('not-an-email')
        await submitForm(wrapper)
        expect(wrapper.text()).toContain('Enter a valid email address')
    })

    it('shows "Password is required" when password is empty on submit', async () => {
        const wrapper = mountView()
        await wrapper.find('input[type="email"]').setValue('user@example.com')
        await submitForm(wrapper)
        expect(wrapper.text()).toContain('Password is required')
    })

    it('does not call login when validation fails', async () => {
        const wrapper = mountView()
        await submitForm(wrapper)
        expect(mockLogin).not.toHaveBeenCalled()
    })
})

describe('LoginView — submission', () => {
    beforeEach(() => {
        vi.clearAllMocks()
    })

    it('calls login with the entered credentials when the form is valid', async () => {
        mockLogin.mockResolvedValue(undefined)
        const wrapper = mountView()
        await wrapper.find('input[type="email"]').setValue('admin@example.com')
        await wrapper.find('input[type="password"]').setValue('secret123')
        await submitForm(wrapper)
        expect(mockLogin).toHaveBeenCalledWith({
            email: 'admin@example.com',
            password: 'secret123',
        })
    })

    it('redirects to "/" after successful login', async () => {
        mockLogin.mockResolvedValue(undefined)
        const wrapper = mountView()
        await wrapper.find('input[type="email"]').setValue('admin@example.com')
        await wrapper.find('input[type="password"]').setValue('secret123')
        await submitForm(wrapper)
        expect(mockPush).toHaveBeenCalledWith('/')
    })

    it('shows a credentials error on a 422 response', async () => {
        mockLogin.mockRejectedValue(new ApiError('Unprocessable Entity', 422, mockResponse(422)))
        const wrapper = mountView()
        await wrapper.find('input[type="email"]').setValue('admin@example.com')
        await wrapper.find('input[type="password"]').setValue('wrong')
        await submitForm(wrapper)
        expect(wrapper.text()).toContain('The provided credentials do not match our records.')
    })

    it('shows a generic error on an unexpected API failure', async () => {
        mockLogin.mockRejectedValue(new Error('Network error'))
        const wrapper = mountView()
        await wrapper.find('input[type="email"]').setValue('admin@example.com')
        await wrapper.find('input[type="password"]').setValue('secret123')
        await submitForm(wrapper)
        expect(wrapper.text()).toContain('Could not log in. Please check your details and try again.')
    })
})
