<template>
    <label
        :for="id"
        class="text-[12px] text-dark font-krub-medium mb-1 block"
        v-if="label"
    >
        {{ label }}
        <span class="text-red" v-if="$attrs.required">*</span>
    </label>
    <div
        class="relative mb-2"
        x-data="{input: $el.getAttribute('data-value')}"
        :data-value="$attrs.value || ''"
        v-bind:class="{ 'has-error': error }"
    >
        <span v-if="icon" class="absolute left-3 top-3">
            <i class="text-[18px] text-yellow" v-bind:class="icon"></i>
        </span>
        <select
            @change="
                $emit('setCode', ($event.target as HTMLInputElement).value)
            "
            class="absolute text-[12px] w-fit px-1 ms-[33px] border-0 mt-[2px] font-krub-medium outline-none py-[10px] bg-transparent"
        >
            <option value="62" :selected="code === '62'">+62</option>
            <option
                v-for="row in phoneCode"
                :value="row.dial_code"
                :selected="code === row.dial_code"
            >
                +{{ row.dial_code }}
            </option>
        </select>
        <input
            type="text"
            x-model="input"
            v-bind="$attrs"
            maxlength="12"
            @keypress="isNumber($event)"
            @input="
                $emit(
                    'update:modelValue',
                    ($event.target as HTMLInputElement).value
                )
            "
            class="bg-[#F2F2F4] w-full rounded-[3px] border-2 outline-none border-[#F2F2F4] focus:bg-white focus:border-yellow font-krub-medium px-4 py-[10px] mb-2 ps-[100px]"
        />
        <small v-if="error" class="mt-[-7px] font-krub-medium mb-4 block text-[11px]">
            {{ error }}
        </small>
        <span class="text-[11px] font-krub-medium mt-[-7px] block mb-4">
            * Please do not put 0 (zero) in front of the phone number
        </span>
    </div>
</template>

<script lang="ts" setup>
import { phoneCode } from "../../libs/phoneCode";
defineProps<{
    label?: string;
    icon?: string;
    help?: string;
    error?: string;
    id?: string;
    code?: string;
}>();

const isNumber = (evt: any) => {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46)
        evt.preventDefault();
    return true;
};
</script>
