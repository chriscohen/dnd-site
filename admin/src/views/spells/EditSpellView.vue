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
import { getSpell, updateSpell } from 'dnd5e-api';
import type { SpellApiResponse} from "@dnd5e/types";
import Tabs from 'primevue/tabs';
import Tab from 'primevue/tab';
import Tablist from 'primevue/tablist';
import TabPanels from 'primevue/tabpanel';
import TabPanel from 'primevue/tabpanel';

const route = useRoute();
const router = useRouter();
const slug = route.params.slug as string;

const spell = ref<SpellApiResponse | null>(null);
const loading = ref(false);
const saving = ref(false);
const errorMessage = ref<string | null>(null);

const spellSchema = z.object({
    slug: z.string().min(1, 'Slug is required').max(255),
    name: z.string().min(1, 'Name is required').max(255),
});
const spellEditionSchema = z.object({
    castingTime: z.string().nullable(),
    description: z.string().nullable(),
    magicSchool: z.string().nullable(),
});

const spellResolver = zodResolver(spellSchema);
const spellEditionResolvers: Record<string, ReturnType<typeof zodResolver>> = {};

onMounted(async () => {
    loading.value = true;
    errorMessage.value = null;

    try {
        spell.value = await getSpell(slug);

        spell.value.editions.forEach(edition => {
            spellEditionResolvers[edition.gameEdition] = zodResolver(spellEditionSchema);
        });
    } catch {
        errorMessage.value = 'Could not load spell. Please try again.';
    } finally {
        loading.value = false;
    }
});

async function submitEdit(event: FormSubmitEvent): Promise<void> {
    if (!event.valid) return;

    saving.value = true;
    errorMessage.value = null;

    try {
        const values = event.values as z.infer<typeof spellSchema>;
        await updateSpell(slug, {
            slug: values.slug,
            name: values.name,
        });
        await router.push({ name: 'spells' });
    } catch {
        errorMessage.value = 'Could not save changes. Please try again.';
    } finally {
        saving.value = false;
    }
}
</script>

<template>
    <div>
        <h1 class="mb-6 text-2xl font-bold">Edit Spell</h1>

        <Message v-if="errorMessage" severity="error" class="mb-4">
            {{ errorMessage }}
        </Message>

        <div v-if="loading" class="text-muted">Loading…</div>

        <div v-else-if="spell" class="grid grid-cols-1 items-start gap-8 md:grid-cols-2">
            <Tabs value="0">
                <TabList>
                    <Tab value="0">Spell</Tab>
                    <Tab
                        v-for="edition in spell.editions"
                        :key="edition.id"
                        :value="edition.id">
                        {{ edition.gameEdition }}
                    </Tab>
                </TabList>

                <TabPanels value="0" class="py-8">
                    <TabPanel value="0" class="flex flex-col gap-4">
                        <Form
                            :resolver="spellResolver"
                            :initial-values="{
                                slug: spell.slug,
                                name: spell.name,
                            }"
                            class="flex flex-col gap-5 md:order-first"
                            @submit="submitEdit"
                        >
                            <div>
                                <label>ID</label>
                                <InputText :model-value="spell.id" disabled class="mt-1 w-full" />
                            </div>

                            <FormField v-slot="$field" name="slug">
                                <label>Slug</label>
                                <InputText v-bind="$field" class="mt-1 w-full" />
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
                    </TabPanel>
                    <TabPanel
                        v-for="edition in spell.editions"
                        :key="edition.id"
                        :value="edition.id"
                    >
                        <Form
                            :resolver="spellEditionResolvers[edition.gameEdition]"
                            :initial-values="{
                                castingTime: null,
                                description: null,
                                magicSchool: null
                            }"
                        >
                            <FormField v-slot="$field" name="castingTime">
                                <label>Casting Time</label>
                                <InputNumber v-bind="$field" class="mt-1 w-full"/>
                                <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                                    {{ $field.error?.message }}
                                </Message>
                            </FormField>
                        </Form>
                    </TabPanel>
                </TabPanels>
            </Tabs>
        </div>
    </div>
</template>

<style scoped>
@reference '../../assets/main.css';

.p-tabpanel {
    @apply py-8;
}
</style>
