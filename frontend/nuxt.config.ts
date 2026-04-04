// https://nuxt.com/docs/api/configuration/nuxt-config
import tailwindcss from "@tailwindcss/vite";

export default defineNuxtConfig({
    app: {
        head: {
            charset: "utf-8",
            htmlAttrs: {
                lang: "en",
            },
            title: 'D&D Site',
            viewport: 'width=device-width, initial-scale=1, maximum-scale=1',
        }
    },
    appConfig: {
        ui: {
            colors: {
                primary: 'red'
            },
            commandPalette: {
                slots: {
                    content: 'absolute top-full left-0 bg-gray-950',
                    input: 'text-lg',
                    item: 'text-lg',
                    label: 'hidden',
                    root: 'relative'
                }
            },
            link: {
                variants: {
                    disabled: {
                        true: 'text-dimmed'
                    }
                }
            },
            popover: {
                slots: {
                    content: 'bg-yellow-100 px-4 py-2 rounded-lg text-gray-900 max-w-128'
                }
            },
            tooltip: {
                slots: {
                    content: 'text-base max-w-128 bg-yellow-100 px-4 py-2 rounded-lg text-gray-800'
                }
            }
        }
    },
    compatibilityDate: '2025-07-15',
    css: ['~/assets/css/main.css',],
    devtools: { enabled: false },
    icon: {
        clientBundle: {
            // Disable scanning to stop the module from generating build errors.
            scan: false,
            sizeLimitKb: 0
        },
        customCollections: [
            {
                prefix: 'dnd',
                dir: './app/assets/icons/dnd'
            },
            {
                prefix: 'magic-school',
                dir: './app/assets/icons/magic-schools'
            }
        ],
        serverBundle: {
            collections: ['lucide']
        }
    },
    image: {
        provider: 'imagekit',
        imagekit: {
            baseURL: 'https://ik.imagekit.io/fw190a8/images',
        }
    },
    imports: {
        dirs: ['types']
    },
    modules: [
        '@nuxt/eslint',
        '@nuxt/fonts',
        '@nuxt/icon',
        '@nuxt/image',
        '@nuxt/ui',
        '@pinia/nuxt',
        'pinia-plugin-persistedstate'
    ],
    nitro: {
        preset: 'aws-amplify'
    },
    postcss: {
        plugins: {
            '@tailwindcss/postcss': {},
        },
    },
    ssr: true,
    runtimeConfig: {
        public: {
            apiUrl: process.env.NUXT_PUBLIC_API_URL || 'http://localhost:8080/api',
            cdnUrl: process.env.NUXT_PUBLIC_CDN_URL || 'https://dnd001.s3.eu-west-2.amazonaws.com',
            baseArmorClass: 10,
            defaultAbilityScore: 10,
            // The number of pixels from the bottom of the list before triggering the next page load.
            infiniteScrollDistance: 10,
            // This is the minimum time in milliseconds between API requests for the infinite scroll elements.
            infiniteScrollInterval: 500,
            siteName: process.env.NUXT_PUBLIC_SITE_NAME || 'Everything D&D'
        }
    },
    typescript: {
        strict: true
    },
    vite: {
        build: {
            commonjsOptions: {
                include: [/dnd5e-ts/, /node_modules/]
            }
        },
        plugins: [
            tailwindcss(),
        ]
    }
})
