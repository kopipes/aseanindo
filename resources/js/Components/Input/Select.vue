<template>
    <div>
        <label
            for=""
            v-if="label"
            class="block font-krub-semibold text-[12px] mb-1"
        >
            {{ label }}
        </label>
        <div class="relative mb-2">
            <select
                v-bind="$attrs"
                class="bg-[#F2F2F4] w-full select-input rounded-[3px] border-2 outline-none border-[#F2F2F4] focus:bg-white focus:border-yellow font-krub-medium px-4 py-[10px] mb-2"
                v-bind:class="{ 'ps-9': icon }"
                @change="$emit('update:modelValue', $event.target.value)"
            >
                <slot />
            </select>
            <span v-if="icon" class="absolute left-3 top-3">
                <i class="text-[18px] text-yellow" v-bind:class="icon"></i>
            </span>
        </div>
    </div>
</template>
<script setup>
defineProps(["label", "icon"]);
</script>
