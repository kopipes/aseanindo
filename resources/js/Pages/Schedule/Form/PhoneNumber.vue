<template>
    <div>
        <p class="text-[13px] font-krub-semibold">
            {{ label }}
            <span v-if="required" class="text-red">*</span>
        </p>
        <div
            class="flex border w-full text-[12px] font-krub-medium rounded-md overflow-hidden"
        >
            <button class="bg-white ps-3 flex gap-2 items-center" :disabled="disable" @click="$emit('showItem')" type="button">
                {{ code || '+62' }}
                <i class="isax icon-arrow-down-1"></i>
            </button>
            <input
                class="px-3 py-2 flex-1 outline-none disabled:bg-white"
                :placeholder="placeholder"
                @keydown="isNumber($event)"
                type="number"
                v-bind="$attrs"
                :disabled="disable"
                @input="$emit('update:modelValue', $event.target.value)"
            />
        </div>
    </div>
</template>
<script setup>
defineProps(["placeholder", "disable", "type", "phones", "label","code","required"]);

const isNumber = (evt) => {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (
        charCode > 31 &&
        (charCode < 48 || charCode > 57) &&
        [46, 44, 43, 45, 101].includes(charCode)
    ) {
        evt.preventDefault();
    }
    if (![8, 17, 65, 46].includes(charCode)) {
        if (evt.target.value.length >= Number(20)) {
            evt.preventDefault();
        }
    }
    return true;
};
</script>
