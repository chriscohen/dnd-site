<script setup lang="ts">
import {format, parseISO} from "date-fns";

const props = defineProps<{
    date: string
    showDay?: boolean
}>();

let day: string, month: string, year: string;

try {
    const parsed = parseISO(props.date);
    day = format(parsed, "dd");
    month = format(parsed, "MMMM");
    year = format(parsed, "y");
} catch (e) { console.error(e); }
</script>

<template>
  <span v-if="date">
      <span v-if="showDay">{{ day }}</span>
      <span>{{ month }}</span>
      <span>{{ year }}</span>
  </span>
</template>
