<template>
        <div
            class="flex border w-full text-[12px] font-krub-medium rounded-md overflow-hidden"
            v-bind:class="{
                'rounded-e-none': !valid && !last,
            }"
        >
            <select
                name=""
                id=""
                class="outline-none ps-1"
                :disabled="valid || disable"
                @change="$emit('setCode', $event.target.value)"
            >
                <option value="62">+62</option>
                <option v-for="opt in phones" :value="opt.dial_code">
                    +{{ opt.dial_code }}
                </option>
            </select>
            <input
                class="px-3 py-2 flex-1 outline-none disabled:bg-white"
                :placeholder="placeholder"
                @keydown="isNumber($event)"
                type="number"
                v-bind="$attrs"
                :disabled="valid || disable"
                @input="$emit('update:modelValue', $event.target.value)"
                v-bind:class="{
                    'rounded-e-none': !valid,
                    'pe-7': valid,
                }"
            />
        </div>
</template>
<script setup>
defineProps(["placeholder", "valid", "disable", "type", "phones", "last"]);

const isNumber = (evt) => {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (
        charCode > 31 &&
        (charCode < 48 || charCode > 57) &&
        [46, 44, 43, 45, 101].includes(charCode)
    ) {
        evt.preventDefault();
    }
    if (![8, 17, 65,46].includes(charCode)) {
        if (evt.target.value.length >= Number(20)) {
            evt.preventDefault();
        }
    }
    return true;
};
</script>
