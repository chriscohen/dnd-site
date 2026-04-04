<script setup lang="ts">
import PageTitle from "~/components/labels/PageTitle.vue";
import {createPerson, type PersonApiResponse} from "~/classes/person";
import CreditsList from "~/components/credits/CreditsList.vue";

const route = useRoute()
const store = usePersonCache();
const data = await store.get({ key: route.params.slug as string }) as PersonApiResponse;
const item = createPerson(data);

definePageMeta({ layout: false });
</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle :loading="store.isLoading" :title="item?.name" back-to="/people"/>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <CreditsList :person="item"/>
        </div>
    </NuxtLayout>
</template>
