<template>
    <div class="max-w-[70%] w-full">
        <div class="rounded-lg  bg-[#F2F4F5] w-full mb-2" 
        v-bind:class="{
            'px-2 py-1 pb-2 border' : type!='subject'
        }">
            <b class="text-[12px] font-krub-semibold" v-if="type!=='subject'">
                <!-- <span v-if="message.message.group">
                    {{ message.message.group }} -
                </span> -->
                {{ message.message.label }}
            </b>
            <div class="flex relative">
                <PhoneNumber
                    :placeholder="`Enter ${message.message.label}`"
                    :type="type"
                    :disable="disable"
                    :valid="valid"
                    :phones="phones"
                    :code="code"
                    :value="content"
                    :last="message.lastOption"
                    @setCode="(val) => (code = val)"
                    v-model="content"
                    v-if="type === 'phone_number'"
                />
                <TextArea
                    :placeholder="`Enter ${message.message.label}`"
                    :type="type"
                    :disable="disable"
                    :valid="valid"
                    :value="content"
                    v-model="content"
                    :last="message.lastOption"
                    v-else-if="type === 'textarea'"
                />
                <Select
                    :placeholder="`Enter ${message.message.label}`"
                    :disable="disable"
                    :valid="valid"
                    :selected="content"
                    :options="message.message.options"
                    :last="message.lastOption"
                    v-model="content"
                    v-else-if="type === 'select'"
                />
                <File
                    :placeholder="`Enter ${message.message.label}`"
                    :disable="disable"
                    :valid="valid"
                    :content="content"
                    :last="message.lastOption"
                    v-model="content"
                    v-else-if="type==='file'"
                />
                <Quantity
                    :placeholder="`Enter ${message.message.label}`"
                    :max="message.message.max_booking"
                    :already_booked="message.message.already_booked"
                    :disable="disable"
                    :valid="valid"
                    :value="content"
                    :last="message.lastOption"
                    v-model="content"
                    v-else-if="type=='quantity'"
                />
                <Subject
                    :disable="disable"
                    :valid="valid"
                    :value="content"
                    :last="message.lastOption"
                    @showSlideSubject="$emit('showSlideSubject',message.message.options)"
                    v-model="content"
                    v-else-if="type=='subject'"
                />
                <Input
                    :placeholder="`Enter ${message.message.label}`"
                    :type="type"
                    :disable="disable"
                    :valid="valid"
                    :value="content"
                    :last="message.lastOption"
                    v-model="content"
                    v-else
                />
                <IconCheck
                    v-if="valid"
                    class="absolute right-3 w-[12px] h-[12px] top-3"
                />
                <button
                    type="button"
                    class="bg-yellow px-3 rounded-md rounded-s-none disabled:bg-[#D9D9D9]"
                    v-if="!valid && !message.lastOption && type!='subject'"
                    :disabled="disable || !content || loading"
                    @click="submit"
                >
                    <IconLoading v-if="loading" />
                    <IconSendWhite v-else />
                </button>
            </div>
            <div class="w-full text-end text-[9px]" v-if="type==='quantity'">
                {{ message.message.already_booked }}/{{ message.message.max_booking }}
            </div>
            <div v-if="type=='phone_number'">
                <span class="text-[9px] text-yellow">*Please do not put 0 (zero) in front of the phone number</span>
            </div>
            <span
                class="text-[9px] block mt-[2px] font-krub-regular text-red"
                v-if="error"
            >
                {{ error }}
            </span>
        </div>
        <button
            v-if="message.lastOption && !disable"
            class="bg-yellow gap-3 flex justify-center items-center text-white text-[12px] w-full py-2 rounded-md mt-2 mb-2 disabled:bg-[#D9D9D9]"
            :disabled="disable || !content || loading"
            @click="submit"
        >
            Submit
            <IconLoading v-if="loading" />
        </button>
    </div>
</template>
<script setup>
import IconSendWhite from "../../../Components/Icon/IconSendWhite.vue";
import IconCheck from "../../../Components/Icon/IconCheck.vue";
import IconLoading from "../../../Components/Icon/IconLoading.vue";
import Input from "./Form/Input.vue";
import Quantity from "./Form/Quantity.vue";
import File from "./Form/File.vue";
import TextArea from "./Form/TextArea.vue";
import Select from "./Form/Select.vue";
import Subject from "./Form/Subject.vue";
import PhoneNumber from "./Form/PhoneNumber.vue";
import { ref } from "vue";

const emit = defineEmits(["sendMessage","showSlideSubject"]);
const props = defineProps(["last", "message", "disable", "phones"]);

const valid = ref(props.message.message.value);
const content = ref(props.message.message.value);
const code = ref(props.message.message.code);
const type = ref(props.message.message.type);
const loading = ref(false);
const error = ref("");
const submit = () => {
    error.value = "";
    loading.value = true;
    var message = content.value;
    if (type.value === "phone_number") {
        message = {
            value: message,
            code: code.value,
        };
    }
    emit(
        "sendMessage",
        message,
        () => {
            loading.value = false;
            valid.value = true;
        },
        (err) => {
            error.value = err.message;
            loading.value = false;
        }
    );
};
</script>
