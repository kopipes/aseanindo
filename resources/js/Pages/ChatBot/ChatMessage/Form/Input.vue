<template>
    <input
        class="border w-full text-[12px] font-krub-medium px-3 py-2 rounded-md outline-none disabled:bg-white"
        :placeholder="placeholder"
        :type="type"
        :disabled="valid || disable"
        :maxlength="type == 'email' ? 50 : 50"
        @input="$emit('update:modelValue', $event.target.value)"
        @keydown="validateInput($event)"
        v-bind="$attrs"
        v-bind:class="{
            'rounded-e-none': !valid && !last,
            'pe-7': valid,
        }"
        @paste="$event.preventDefault()"
    />
</template>
<script setup>
const props = defineProps(["placeholder", "valid", "disable", "type", "last"]);
const validateInput = (evt) => {
    const type = props.type || "text";
    var charCode = evt.which ? evt.which : evt.keyCode;
    var ctrlKey = evt.ctrlKey
    console.log({ctrlKey,charCode})
    if (type == "number") {
        if (
            charCode > 31 &&
            (charCode < 48 || charCode > 57) &&
            [46, 44, 43, 45, 101].includes(charCode)
        ) {
            evt.preventDefault();
        }
    }
    if(charCode==65 && ctrlKey){
       return true 
    }
    if (![8,39,37].includes(charCode)) {
        if (["number", "text"].includes(type)) {
            if (evt.target.value.length >= Number(50)) {
                evt.preventDefault();
            }
        } else if (type == "email") {
            if (evt.target.value.length >= Number(50)) {
                evt.preventDefault();
            }
        }
    }
    return true;
};
</script>
