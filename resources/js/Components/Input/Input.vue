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
            <input
                v-bind="$attrs"
                class="bg-[#F2F2F4] w-full rounded-[3px] border-2 outline-none border-[#F2F2F4] focus:bg-white focus:border-yellow font-krub-medium px-4 py-[10px] mb-2"
                :type="type || 'text'"
                :placeholder="placeholder || label"
                @keypress="isNumber($event, type || 'text')"
                @input="$emit('update:modelValue', $event.target.value)"
                v-bind:class="{ 'ps-9': icon }"
            />
            <span v-if="icon" class="absolute left-3 top-3">
                <i class="text-[18px] text-yellow" v-bind:class="icon"></i>
            </span>
        </div>
        <small v-if="error" class="mt-[-12px] font-krub-medium mb-4 block text-[11px] text-red">
            {{ error }}
        </small>
    </div>
</template>
<script setup>
defineProps(["label", "icon", "placeholder", "type","error"]);

const isNumber = (evt, type) => {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (
        type === "number" &&
        charCode > 31 &&
        (charCode < 48 || charCode > 57) &&
        charCode !== 46
    )
        evt.preventDefault();
    return true;
};
</script>
