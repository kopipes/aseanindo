<template>
     <div>
         <p class="text-[13px] font-krub-semibold">
             {{ label }}
         </p>
         <textarea
             class="border w-full text-[12px] font-krub-medium px-3 py-2 mt-[4px] rounded-md outline-none disabled:bg-white resize-none"
             :placeholder="placeholder"
             :type="type"
             :disabled="disable"
             maxlength="255"
             rows="3"
             @input="$emit('update:modelValue', $event.target.value)"
             v-bind="$attrs"
             >{{ $attrs.value }}</textarea
         >
     </div>
 </template>
 <script setup>
 defineProps(["placeholder", "disable", "type","label"]);
 </script>
 