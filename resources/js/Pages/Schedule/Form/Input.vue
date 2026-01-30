<template>
    <div>
        <p class="text-[13px] font-krub-semibold">
            {{ label }}
            <span v-if="required" class="text-red">*</span>
        </p>
        <input
            class="border w-full px-3 py-2 rounded-md text-[12px] font-krub-medium mt-[4px] outline-none disabled:bg-white"
            :placeholder="placeholder"
            :type="type"
            :disabled="disable"
            :maxlength="type == 'email' ? 50 : 50"
            @input="$emit('update:modelValue', $event.target.value)"
            @keydown="validateInput($event)"
            v-bind="$attrs"
            @paste="$event.preventDefault()"
        />
        <span v-if="help" class="text-[11px] inline-block">
            {{ help }}
        </span>
    </div>
</template>
<script setup>
const props = defineProps(["placeholder", "disable", "type", "label","required","help"]);
const validateInput = (evt) => {
    const type = props.type || "text";
    var charCode = evt.which ? evt.which : evt.keyCode;
    var ctrlKey = evt.ctrlKey;
    if (type == "number") {
        if (
            charCode > 31 &&
            (charCode < 48 || charCode > 57) &&
            [46, 44, 43, 45, 101].includes(charCode)
        ) {
            evt.preventDefault();
        }
    }
    if (charCode == 65 && ctrlKey) {
        return true;
    }
    if (![8, 39, 37].includes(charCode)) {
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

<style scoped>
input[type="date"],
input[type="time"] {
    position: relative;
    cursor: pointer;
}
input[type="date"]::-webkit-calendar-picker-indicator,
input[type="time"]::-webkit-calendar-picker-indicator {
    display: block;
    top: 0;
    right: 0;
    height: 100%;
    width: 100%;
    position: absolute;
    background: transparent;
    cursor: pointer;
}
input[type="date"]::after {
    content: "\ebcd";
    font-family: "iconsax-outline";
    position: absolute;
    right: 9px;
    top: 8px;
    font-size: 15px;
}
input[type="time"]::after {
    content: "\e9b6";
    font-family: "iconsax-outline";
    position: absolute;
    right: 9px;
    top: 8px;
    font-size: 15px;
    pointer-events: none;
}
</style>
