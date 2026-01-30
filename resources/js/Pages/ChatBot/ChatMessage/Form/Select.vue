<template>
    <select
        class="border w-full text-[12px] font-krub-medium ps-2 py-2 rounded-md outline-none disabled:bg-white select-input"
        :placeholder="placeholder"
        :type="type"
        :disabled="valid || disable"
        maxlength="25"
        @input="$emit('update:modelValue', $event.target.value)"
        v-bind="$attrs"
        v-bind:class="{
            'rounded-e-none': !valid && !last,
            'pe-7': valid,
        }"
    >
        <option value="">Select</option>
        <option 
            v-for="opt in options" 
            :value="opt"
            :selected="selected===opt"
        >
            {{ opt }}
        </option>
    </select>
</template>
<script setup>
defineProps(["placeholder", "valid", "disable", "type", "options","last","selected"]);
</script>
