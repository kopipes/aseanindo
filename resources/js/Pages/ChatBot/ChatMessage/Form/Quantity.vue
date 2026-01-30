<template>
    <div class="w-full flex flex-col">
        <input
            class="border w-full text-[12px] font-krub-medium px-3 py-2 rounded-md outline-none disabled:bg-white"
            :placeholder="placeholder"
            :type="type"
            :disabled="valid || disable"
            :maxlength="type == 'email' ? 50 : 25"
            @input="$emit('update:modelValue', $event.target.value)"
            v-bind="$attrs"
            v-bind:class="{
                'rounded-e-none': !valid && !last,
                'pe-7': valid,
            }"
        />
    </div>
</template>
<script setup>
defineProps([
    "placeholder",
    "valid",
    "disable",
    "type",
    "last",
    "max",
    "already_booked",
]);

</script>
