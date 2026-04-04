<script setup lang="ts">
import {useCompanyCache} from "~/stores/Store";
import PageTitle from "~/components/labels/PageTitle.vue";
import ProductList from "~/components/lists/ProductList.vue";
import {createCompany, type CompanyApiResponse} from "~/classes/company";

const route = useRoute();
const store = useCompanyCache();
const data: CompanyApiResponse = await store.get({ key: route.params.slug as string}) as CompanyApiResponse;
const item = createCompany(data);

useHead({ title: item?.name ?? 'Loading' });
definePageMeta({ layout: false });
</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle :title="item?.name" back-to="/companies">
                <template #subtitle>Company</template>/
            </PageTitle>
        </template>

        <div v-if="item" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 h-full">
            <div id="column-left flex flex-col">
                <NuxtImg v-if="item?.logo" :src="item.logo.url" :alt="item?.name + 'Logo'"/>
            </div>

            <div id="column-mid" class="flex flex-col min-h-0">
                <ProductList :publisher="item.slug" class="w-full min-h-0"/>
            </div>

            <div id="column-right" class="flex flex-col"/>
        </div>
    </NuxtLayout>
</template>
