<script setup lang="ts">
import PersonLabel from "~/components/labels/PersonLabel.vue";
import DndHeading from "~/components/headings/DndHeading.vue";
import type {Person} from "~/classes/person";
import {useBookCreditCache} from "~/stores/Store";
import {type BookCredit, type BookCreditApiResponse, createBookCredit} from "~/classes/sources/bookCredit";

const props = defineProps<{
    source?: string
}>();

const open = ref<boolean>(false);

const store = useBookCreditCache();

watch(open, (isNowOpen) => {
    if (isNowOpen && !data.value) execute();
})

const { pending, data, execute } = await useLazyAsyncData(
    'bookCredits',
    async () => await store.list({ path: 'source/' + props.source + '/credits' }) as BookCreditApiResponse[],
    { immediate: false }
)

const bookCredits = computed(() => data.value?.map(createBookCredit) as BookCredit[]);

const creditsByRole = () => {
    const roles: Record<string, Person[]> = {};

    // Build an object keyed by each role.
    bookCredits.value?.forEach((credit: BookCredit) => {
        if (credit.role) roles[credit.role] = [];
    });

    // Add people from the list of credits to each role key in the roles object.
    Object.keys(roles).forEach((role: string) => {
        roles[role] = bookCredits.value?.filter(
            (credit: BookCredit) => credit?.role === role && credit.person
        )?.map(
            (credit: BookCredit) => credit.person ?? {}
        ) ?? [];
    });

    return roles;
}

const items = computed(() => creditsByRole());
</script>

<template>
    <UCollapsible v-model:open="open" class="h-full group">
        <DndHeading size="2" underline-color="red-800" underline class="flex w-full justify-between cursor-pointer group">
            Credits

            <template #controls>
                <UIcon
                    v-if="!pending"
                    name="i-lucide-chevron-up"
                    class="group group-data-[state=open]:rotate-180 text-4xl transition-transform duration-300"
                />
                <UIcon v-else name="i-lucide-loader-circle" class="animate-spin"/>
            </template>
        </DndHeading>

        <template #content>
            <div v-for="role in Object.keys(items)" :key="role">
                <DndHeading size="3" underline class="mt-2">{{ role }}</DndHeading>

                <div class="mt-2 [&>a:not(:last-child)]:after:content-[','] [&>a:not(:last-child)]:mr-1">
                    <NuxtLink
                        v-for="person in items[role] as Person[]"
                        :key="person.slug"
                        :to="`/people/${person.slug}`"
                        class="link"
                    >
                        <PersonLabel :person="person"/>
                    </NuxtLink>
                </div>
            </div>
        </template>
    </UCollapsible>
</template>
