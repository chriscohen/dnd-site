<script setup lang="ts">
import {useRoute} from "#app";
import {useCharacterClassCache} from "~/stores/Store";
import PageTitle from "~/components/labels/PageTitle.vue";
import {type CharacterClassApiResponse, createCharacterClass} from "~/classes/characterClasses/characterClass";

const route = useRoute();
const store = useCharacterClassCache();
const data: CharacterClassApiResponse = await store.get(route.params.slug as string) as CharacterClassApiResponse;
const item = createCharacterClass(data);

useHead({ title: item?.name ?? 'Loading' });
definePageMeta({ layout: false });
</script>

<template>
    <NuxtLayout name="default">
        <template #pageTitle>
            <PageTitle :title="item?.name ?? 'Title'" back-to="/classes">
                <template #subtitle>Character Class</template>
            </PageTitle>
        </template>
    </NuxtLayout>
</template>
