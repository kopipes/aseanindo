<template>
    <ul class="w-full flex flex-col gap-2 mt-2">
        <li v-for="(form, index) in forms" :key="index" >
            <CardGroup :label="form.label" v-if="form.type == 'group'">
                <ul class="flex flex-col gap-2">
                    <li v-for="item in form.items">
                        <Input
                            v-if="
                                ['text', 'email', 'number', 'date','time'].includes(
                                    item.type
                                )
                            "
                            :placeholder="`Enter ${item.label}`"
                            :type="item.type"
                            :label="item.label"
                            :disabled="disabled"
                            :required="item.is_default"
                            v-model="data[item.slug]"
                        />
                        <PhoneNumber
                            :placeholder="`Enter ${item.label}`"
                            :label="item.label"
                            :phones="phoneCodes"
                            :code="`+${data[`${item.slug}_code`]}`"
                            :disable="disabled"
                            v-model="data[item.slug]"
                            @showItem="
                                $emit(
                                    'showPhoneCode',
                                    data[`${item.slug}_code`],
                                    (properties) => {
                                        choosePhone(properties, item);
                                    }
                                )
                            "
                            :required="item.is_default"
                            v-if="item.type === 'phone_number'"
                        />
                        <Textarea
                            v-if="item.type == 'textarea'"
                            :placeholder="`Enter ${item.label}`"
                            :type="item.type"
                            :label="item.label"
                            :disabled="disabled"
                            v-model="data[item.slug]"
                        />

                        <InputFile
                            :placeholder="`Enter ${item.label}`"
                            :label="item.label"
                            :content="data[item.slug]"
                            :disable="disabled"
                            v-model="data[item.slug]"
                            v-else-if="item.type === 'file'"
                        />
                        <ChooseSelect
                            :label="`Choose ${item.label}`"
                            @fetchItem="
                                (callback) => fetchSelectItem(callback, item)
                            "
                            :disable="disabled"
                            :value="data[item.slug]"
                            v-if="item.type == 'select'"
                        />
                    </li>
                </ul>
            </CardGroup>

            <ChooseSelect
                :label="form.label"
                @fetchItem="(callback) => fetchSubject(callback, form)"
                :disable="disabled"
                :value="findNameByValueIterative(form.options,data.subject_id)?.label"
                v-if="form.type == 'subject'"
            />
        </li>
    </ul>
</template>
<script setup lang="ts">
import CardGroup from "./Form/CardGroup.vue";
import Input from "./Form/Input.vue";
import PhoneNumber from "./Form/PhoneNumber.vue";
import Textarea from "./Form/Textarea.vue";
import InputFile from "./Form/InputFile.vue";
import ChooseSelect from "./Form/ChooseSelect.vue";
import { findNameByValueIterative } from "../../libs/function";

const emit = defineEmits(["setPopupSelect", "showPhoneCode"]);
const props = defineProps(["phoneCodes", "forms", "data","disabled"]);

const choosePhone = (properties, form) => {
    properties.callback();
    props.data[`${form.slug}_code`] = properties.value;
};

const fetchSelectItem = (callback, item) => {
    emit("setPopupSelect", {
        items: item.options.map((option) => {
            return {
                value: option,
                label: option,
            };
        }),
        title: item.label,
        type: item.slug,
        search: false,
        choose: (properties) => {
            properties.callback();
            props.data[item.slug] = properties.value;
        },
        value: props.data[item.slug],
    });
    document.getElementById("toggle-slide-popup").click();
    callback();
};

const fetchSubject = (callback, item) => {
    emit("setPopupSelect", {
        items: item.options,
        title: item.label,
        type: 'subject',
        search: false,
        choose: (properties) => {
            properties.callback();
            props.data.subject_id = properties.value;
        },
        value: props.data.subject_id,
    });
    document.getElementById("toggle-slide-popup").click();
    callback();
};
</script>
