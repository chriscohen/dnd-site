<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { z } from 'zod';
import { Form, FormField } from '@primevue/forms';
import type { FormSubmitEvent } from '@primevue/forms';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import Button from 'primevue/button';
import Image from 'primevue/image';
import InputText from 'primevue/inputtext';
import Message from 'primevue/message';
import type { CampaignSettingApiResponse } from '@dnd5e/types';
import { getCampaignSetting, getCompanies, updateCampaignSetting } from 'dnd5e-api';
import ApiSelect from '@/components/ApiSelect.vue';

const route = useRoute();
const router = useRouter();
const slug = route.params.slug as string;

const campaignSetting = ref<CampaignSettingApiResponse | null>(null);
const loading = ref(false);
const saving = ref(false);
const errorMessage = ref<string | null>(null);

const schema = z.object({
    name: z.string().min(1, 'Name is required'),
    slug: z.string().min(1, 'Slug is required'),
    description: z.string().nullable(),
    publisherId: z.string().nullable(),
    shortName: z.string().nullable(),
});

const resolver = zodResolver(schema);

onMounted(async () => {
    loading.value = true;
    errorMessage.value = null;

    try {
        campaignSetting.value = await getCampaignSetting(slug);
    } catch {
        errorMessage.value = 'Could not load campaign setting. Please try again.';
    } finally {
        loading.value = false;
    }
});

async function submitEdit(event: FormSubmitEvent): Promise<void> {
    if (!event.valid) return;

    saving.value = true;
    errorMessage.value = null;

    try {
        const values = event.values as z.infer<typeof schema>;
        await updateCampaignSetting(slug, {
            slug: values.slug,
            description: values.description || null,
            name: values.name,
            publisherId: values.publisherId || null,
            shortName: values.shortName || null,
        });
        await router.push({ name: 'campaign-settings' });
    } catch {
        errorMessage.value = 'Could not save changes. Please try again.';
    } finally {
        saving.value = false;
    }
}
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Edit Campaign Setting</h1>

        <Message v-if="errorMessage" severity="error" class="mb-4">
            {{ errorMessage }}
        </Message>

        <div v-if="loading" class="text-muted">Loading…</div>

        <div v-else-if="campaignSetting" class="grid grid-cols-1 items-start gap-8 md:grid-cols-2">

        <div v-if="campaignSetting.logo?.url" class="md:order-last">
            <label class="block text-sm font-medium">Logo</label>
            <div class="mt-1 inline-flex w-96 items-center justify-center rounded-lg border border-surface-700 bg-surface-800 p-2">
                <Image
                    :src="campaignSetting.logo.url"
                    :alt="campaignSetting.name"
                    class="w-full object-contain"
                    preview
                />
            </div>
        </div>

        <Form
            :resolver
            :initial-values="{
                name:        campaignSetting.name,
                slug:        campaignSetting.slug,
                shortName:   campaignSetting.shortName ?? '',
                description: campaignSetting.description,
                publisherId: campaignSetting.publisher?.id ?? null,
            }"
            class="flex flex-col gap-5 md:order-first"
            @submit="submitEdit"
        >
            <div>
                <label class="block text-sm font-medium">ID</label>
                <InputText :model-value="campaignSetting.id" disabled class="mt-1 w-full" />
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

            <FormField v-slot="$field" name="publisherId">
                <label class="block text-sm font-medium">Publisher</label>
                <ApiSelect v-bind="$field" :fetch="getCompanies" />
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

            <div class="flex gap-8 items-center">
                <Button asChild variant="outlined">
                    <RouterLink :to="{ name: 'campaign-settings' }">Cancel</RouterLink>
                </Button>
                <Button type="submit" label="Save changes" :loading="saving" />
            </div>
        </Form>
        </div>
    </div>
</template>
