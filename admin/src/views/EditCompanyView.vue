<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { z } from 'zod';
import { Form, FormField } from '@primevue/forms';
import type { FormSubmitEvent } from '@primevue/forms';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Message from 'primevue/message';
import { getCompany, type Company } from '@/api/companies';

const route = useRoute();
const slug = route.params.slug as string;

const company = ref<Company | null>(null);
const loading = ref(false);
const errorMessage = ref<string | null>(null);

const schema = z.object({
    name:        z.string().min(1, 'Name is required'),
    slug:        z.string().min(1, 'Slug is required'),
    shortName:   z.string().nullable(),
    website:     z.string().url('Enter a valid URL').or(z.literal('')).nullable(),
    productUrl:  z.string().nullable(),
});

const resolver = zodResolver(schema);

onMounted(async () => {
    loading.value = true;
    errorMessage.value = null;

    try {
        company.value = await getCompany(slug);
    } catch {
        errorMessage.value = 'Could not load company. Please try again.';
    } finally {
        loading.value = false;
    }
});

function submitEdit(_event: FormSubmitEvent): void {
    // TODO: implement save
}
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Edit Company</h1>

        <Message v-if="errorMessage" severity="error" class="mb-4">
            {{ errorMessage }}
        </Message>

        <div v-if="loading" class="text-muted">Loading…</div>

        <Form
            v-else-if="company"
            :resolver
            :initial-values="{
                name:       company.name,
                slug:       company.slug,
                shortName:  company.shortName ?? '',
                website:    company.website ?? '',
                productUrl: company.productUrl ?? '',
            }"
            class="flex max-w-lg flex-col gap-5"
            @submit="submitEdit"
        >
            <div>
                <label class="block text-sm font-medium">ID</label>
                <InputText :model-value="company.id" disabled class="mt-1 w-full" />
            </div>

            <FormField v-slot="$field" name="name">
                <label class="block text-sm font-medium">Name</label>
                <InputText v-bind="$field" class="mt-1 w-full" />
                <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                    {{ $field.error?.message }}
                </Message>
            </FormField>

            <FormField v-slot="$field" name="slug">
                <label class="block text-sm font-medium">Slug</label>
                <InputText v-bind="$field" class="mt-1 w-full" />
                <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                    {{ $field.error?.message }}
                </Message>
            </FormField>

            <FormField v-slot="$field" name="shortName">
                <label class="block text-sm font-medium">Short name</label>
                <InputText v-bind="$field" class="mt-1 w-full" placeholder="Optional" />
                <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                    {{ $field.error?.message }}
                </Message>
            </FormField>

            <FormField v-slot="$field" name="website">
                <label class="block text-sm font-medium">Website</label>
                <InputText v-bind="$field" type="url" class="mt-1 w-full" placeholder="Optional" />
                <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                    {{ $field.error?.message }}
                </Message>
            </FormField>

            <FormField v-slot="$field" name="productUrl">
                <label class="block text-sm font-medium">Product URL template</label>
                <InputText v-bind="$field" class="mt-1 w-full" placeholder="e.g. product/{{id}}" />
                <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                    {{ $field.error?.message }}
                </Message>
            </FormField>

            <div>
                <Button type="submit" label="Save changes" />
            </div>
        </Form>
    </div>
</template>
