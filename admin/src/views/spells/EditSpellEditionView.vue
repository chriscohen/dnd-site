<script setup lang="ts">
import { ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { z } from 'zod';
import { Form, FormField } from '@primevue/forms';
import type { FormSubmitEvent } from '@primevue/forms';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Message from 'primevue/message';
import Checkbox from 'primevue/checkbox';
import {getGameEditions, getSpell, getSpellEdition, updateSpellEdition} from 'dnd5e-api';
import type {SpellApiResponse, SpellEditionApiResponse} from "@dnd5e/types";
import ApiSelect from "@/components/ApiSelect.vue";

const route = useRoute();
const router = useRouter();

const spell = ref<SpellApiResponse | null>(null);
const spellEdition = ref<SpellEditionApiResponse | null>(null);
const loading = ref(false);
const saving = ref(false);
const errorMessage = ref<string | null>(null);

const schema = z.object({
    focus: z.string().max(255).nullable(),
    gameEdition: z.string(),
    hasSpellResistance: z.boolean(),
    isDefault: z.boolean(),
    magicSchoolId: z.string(),
    rarity: z.number().int().positive(),
    spellId: z.string(),
});

const resolver = zodResolver(schema);

watch(
    () => route.params.id as string,
    async (id) => {
        loading.value = true;
        errorMessage.value = null;

        try {
            spellEdition.value = await getSpellEdition(id);
            spell.value = await getSpell(spellEdition.value.spell.id);
        } catch {
            errorMessage.value = 'Could not load spell edition. Please try again.';
        } finally {
            loading.value = false;
        }
    },
    { immediate: true },
);

async function submitEdit(event: FormSubmitEvent): Promise<void> {
    if (!event.valid) return;

    saving.value = true;
    errorMessage.value = null;

    try {
        const values = event.values as z.infer<typeof schema>;
        await updateSpellEdition(route.params.id as string, {
            focus: values.focus,
            gameEdition: values.gameEdition,
            hasSpellResistance: values.hasSpellResistance,
            isDefault: values.isDefault,
            magicSchoolId: values.magicSchoolId,
            rarity: values.rarity,
            spellId: values.spellId,
        });
        await router.push({ name: 'spell-editions' });
    } catch {
        errorMessage.value = 'Could not save changes. Please try again.';
    } finally {
        saving.value = false;
    }
}
</script>

<template>
    <div>
        <h1 class="mb-6">
            Edit
            <template v-if="spell && spellEdition">{{spell.name }} {{ spellEdition.gameEdition }}</template>
            <template v-else>Spell Edition</template>
        </h1>

        <Message v-if="errorMessage" severity="error" class="mb-4">
            {{ errorMessage }}
        </Message>

        <div v-if="loading" class="text-muted">Loading…</div>

        <div v-else-if="spellEdition" class="grid grid-cols-1 items-start gap-8 md:grid-cols-2">
            <div v-if="spell" class="md:order-last">
                <h2 class="mb-4">{{ spell.name }}</h2>

                <ul>
                    <li v-for="edition in spell.editions" :key="edition.id">
                        <RouterLink
                            v-if="edition.id !== spellEdition.id"
                            :to="{ name: 'spell-edition.edit', params: { id: edition.id }}"
                        >
                            Edit {{ edition.gameEdition }}
                        </RouterLink>
                        <span v-else class="font-italic">{{ edition.gameEdition }}</span>
                    </li>
                </ul>
            </div>

            <Form
                :resolver
                :initial-values="{
                    id: spellEdition.id
                }"
                class="flex flex-col gap-5 md:order-first"
                @submit="submitEdit"
            >
                <div>
                    <label>ID</label>
                    <InputText :model-value="spellEdition.id" disabled class="mt-1 w-full" />
                </div>

                <FormField v-slot="$field" name="focus">
                    <label>Focus</label>
                    <InputText v-bind="$field" class="mt-1 w-full" />
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <FormField v-slot="$field" name="gameEdition">
                    <label for="gameEdition">Game Edition</label>
                    <ApiSelect v-bind="$field" :fetch="getGameEditions" option-value="shortName"/>
                    <Message v-if="$field.invalid" severity="error" size="small" variant="simple" class="mt-1">
                        {{ $field.error?.message }}
                    </Message>
                </FormField>

                <FormField v-slot="$field" name="hasSpellResistance">
                    <label>Spell Resistance</label>
                    <Checkbox v-bind="$field" binary />
                </FormField>

                <div class="flex gap-8 items-center">
                    <Button asChild variant="outlined">
                        <RouterLink :to="{ name: 'spell-editions' }">Cancel</RouterLink>
                    </Button>
                    <Button type="submit" label="Save changes" :loading="saving" />
                </div>
            </Form>
        </div>
    </div>
</template>
