<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { z } from 'zod';
import { Form, FormField } from '@primevue/forms';
import type { FormSubmitEvent } from '@primevue/forms';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Message from 'primevue/message';
import { getPerson, updatePerson } from 'dnd5e-api';
import type { PersonApiResponse} from "@dnd5e/types";

const route = useRoute();
const router = useRouter();
const slug = route.params.slug as string;

const person = ref<PersonApiResponse | null>(null);
const loading = ref(false);
const saving = ref(false);
const errorMessage = ref<string | null>(null);

const schema = z.object({
    slug: z.string().min(1, 'Slug is required').max(255),
    firstName: z.string().min(1, 'First name is required').max(255),
    lastName: z.string().min(1, 'Last name is required').max(255),
    initials: z.string().max(255).nullable(),
    middleNames: z.string().max(255).nullable(),
    artstation: z.string().max(255).nullable(),
    instagram: z.string().max(255).nullable(),
    twitter: z.string().max(255).nullable(),
    youtube: z.string().max(255).nullable(),
});

const resolver = zodResolver(schema);

onMounted(async () => {
    loading.value = true;
    errorMessage.value = null;

    try {
        person.value = await getPerson(slug);
    } catch {
        errorMessage.value = 'Could not load person. Please try again.';
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
        await updatePerson(slug, {
            slug: values.slug,
            firstName: values.firstName,
            lastName: values.lastName,
            initials: values.initials || null,
            middleNames: values.middleNames || null,
            artstation: values.artstation || null,
            instagram: values.instagram || null,
            twitter: values.twitter || null,
            youtube: values.youtube || null,
        });
        await router.push({ name: 'people' });
    } catch {
        errorMessage.value = 'Could not save changes. Please try again.';
    } finally {
        saving.value = false;
    }
}
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Edit Person</h1>

        <Message v-if="errorMessage" severity="error" class="mb-4">
            {{ errorMessage }}
        </Message>

        <div v-if="loading" class="text-muted">Loading…</div>

        <div v-else-if="person" class="grid grid-cols-1 items-start gap-8 md:grid-cols-2">
            <Form
                :resolver
                :initial-values="{
                slug: person.slug,
                firstName: person.firstName,
                lastName: person.lastName,
                initials: person.initials,
                middleNames: person.middleNames,
                artstation: person.artstation,
                instagram: person.instagram,
                twitter: person.twitter,
                youtube: person.youtube,
            }"
                class="flex flex-col gap-5 md:order-first"
                @submit="submitEdit"
            >
                <div>
                    <label class="block text-sm font-medium">ID</label>
                    <InputText :model-value="person.id" disabled class="mt-1 w-full" />
                </div>

                <FormField v-slot="$field" name="slug">
                    <label class="block text-sm font-medium">Slug</label>
                    <InputText v-bind="$field" class="mt-1 w-full" />
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <FormField v-slot="$field" name="firstName">
                    <label class="block text-sm font-medium">First Name</label>
                    <InputText v-bind="$field" class="mt-1 w-full" />
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <FormField v-slot="$field" name="middleNames">
                    <label class="block text-sm font-medium">Middle Names</label>
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <FormField v-slot="$field" name="initials">
                    <label class="block text-sm font-medium">Initials</label>
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <FormField v-slot="$field" name="lastName">
                    <label class="block text-sm font-medium">Last Name</label>
                    <InputText v-bind="$field" class="mt-1 w-full" />
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <FormField v-slot="$field" name="artstation">
                    <label class="block text-sm font-medium">Artstation</label>
                    <InputText v-bind="$field" class="mt-1 w-full" placeholder="Optional" />
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <FormField v-slot="$field" name="instagram">
                    <label class="block text-sm font-medium">Instagram</label>
                    <InputText v-bind="$field" type="url" class="mt-1 w-full" placeholder="Optional" />
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <FormField v-slot="$field" name="twitter">
                    <label class="block text-sm font-medium">Twitter</label>
                    <InputText v-bind="$field" class="mt-1 w-full" placeholder="Optional" />
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <FormField v-slot="$field" name="youtube">
                    <label class="block text-sm font-medium">YouTube</label>
                    <InputText v-bind="$field" type="url" class="mt-1 w-full" placeholder="Optional" />
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <div class="flex gap-8 items-center">
                    <Button asChild variant="outlined">
                        <RouterLink :to="{ name: 'companies' }">Cancel</RouterLink>
                    </Button>
                    <Button type="submit" label="Save changes" :loading="saving" />
                </div>
            </Form>
        </div>
    </div>
</template>
