<script setup>
const props = defineProps({
    dice: {
        type: String,
        required: true
    }
});

const re = /(\d+)d(\d+)([+-]?)(\d+)?/;
const result = props.dice.match(re);
const numberOfDice = result[1];
const diceFaces = result[2];
const hasModifier = result[3] !== '';
const plusMinus = result[3];
const modifier = result[4];

const min = (numberOfDice * 1) + (hasModifier ? (plusMinus === '-' ? (modifier * -1) : (modifier * 1)) : 0);
const max = (numberOfDice * diceFaces) + (hasModifier ? (plusMinus === '-' ? (modifier * -1) : (modifier * 1)) : 0);
</script>

<template>
    <strong>
        {{numberOfDice}}d{{diceFaces}}
        <template v-if="hasModifier">&nbsp;{{plusMinus}} {{modifier}}</template>
        ({{min}}—{{max}})
    </strong>
</template>
