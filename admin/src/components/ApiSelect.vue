<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Select from 'primevue/select';

defineOptions({ inheritAttrs: false });

const props = withDefaults(defineProps<{
    fetch: () => Promise<{ data: unknown[] } | unknown[]>;
    optionLabel?: string;
    optionValue?: string;
    placeholder?: string;
    value?: unknown;
    invalid?: boolean;
}>(), {
    optionLabel: 'name',
    optionValue: 'id',
    placeholder: 'Select…',
});

const options = ref<unknown[]>([]);
const loadingOptions = ref(false);

onMounted(async () => {
    loadingOptions.value = true;
    try {
        const result = await props.fetch();
        options.value = Array.isArray(result) ? result : result.data;
    } finally {
        loadingOptions.value = false;
    }
});
</script>

<template>
    <Select
        v-bind="$attrs"
        :model-value="value"
        :options="options"
        :option-label="optionLabel"
        :option-value="optionValue"
        :placeholder="placeholder"
        :loading="loadingOptions"
        :invalid="invalid"
        class="mt-1 w-full"
    />
</template>
