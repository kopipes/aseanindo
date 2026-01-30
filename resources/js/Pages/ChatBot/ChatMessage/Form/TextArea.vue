<template>
     <textarea
         class="border w-full text-[12px] font-krub-medium px-3 py-2 rounded-md outline-none disabled:bg-white resize-none"
         :placeholder="placeholder"
         :type="type"
         :disabled="valid || disable"
         maxlength="255"
         @input="$emit('update:modelValue', $event.target.value)"
         v-bind="$attrs"
         v-bind:class="{
             'rounded-e-none': !valid && !last,
             'pe-7': valid,
         }"
     >{{ $attrs.value }}</textarea>
 </template>
 <script setup>
 defineProps(["placeholder", "valid", "disable", "type","last"]);
 </script>
 