<script setup lang="ts">
const summaryCards = [
    {
        label: 'Creatures',
        value: '—',
        description: 'Manage creature records, editions, stats, and metadata.',
    },
    {
        label: 'Sources',
        value: '—',
        description: 'Manage sourcebooks, references and edition mappings.',
    },
    {
        label: 'Media',
        value: '—',
        description: 'Manage images, tokens, and other uploaded assets.',
    }
];

</script>

<template>
    <div>
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-wide text-indigo-400">
                Dashboard
            </p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-white">
                Welcome back
            </h1>
            <p class="mt-3 max-w-2xl text-sm text-slate-400">
                This is the starting point for managing site content.
            </p>
        </div>

        <section class="grid gap-4 md:grid-cols-3">
            <article
                v-for="card in summaryCards"
                :key="card.label"
                class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-400">
                            {{ card.label }}
                        </p>
                        <p class="mt-2 text-3xl font-bold text-white">
                            {{ card.value }}
                        </p>
                    </div>

                    <div class="rounded-lg bg-indigo-500/10 px-2.5 py-1 text-xs font-semibold text-indigo-300">
                        API
                    </div>
                </div>

                <p class="mt-4 text-sm leading-6 text-slate-400">
                    {{ card.description }}
                </p>
            </article>
        </section>
    </div>
</template>
