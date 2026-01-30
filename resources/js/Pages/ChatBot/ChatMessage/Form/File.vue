<template>
    <div class="w-full">
        <input
            type="file"
            @change="changeFile($event)"
            ref="input"
            class="h-[0px] hidden"
            v-bind="$attrs"
        />

        <button
            v-if="!file && !content"
            @click="input?.click()"
            class="border border-dashed border-yellow font-krub-semibold flex justify-center items-center h-[37px] rounded-md cursor-pointer gap-2 text-[11px] bg-white w-full"
            v-bind:class="{
                'rounded-e-none': !valid && !last,
                'pe-7': valid,
            }"
            :disabled="disable"
            type="button"
        >
            <UploadIcon />
            Browse Files
        </button>

        <div
            class="border flex w-full py-[7px] rounded-lg px-4 items-center justify-between bg-white"
            v-bind:class="{
                'rounded-e-none': !valid && !last,
                'pe-7': valid,
            }"
            v-if="file"
        >
            <a :href="fileUrl()" target="_blank" class="w-full">
                <i class="me-2 isax-b icon-eye w-[40px] absolute mt-1"></i>
                <b class="text-[11px] wrap-text-line ms-[30px]">Show File</b>
            </a>
            <span
                class="isax-b icon-close-circle cursor-pointer"
                v-if="!valid"
                @click="removeFile()"
            ></span>
        </div>
    </div>
</template>

<script setup>
import UploadIcon from "../../../../Components/Icon/UploadIcon.vue";
import { ref, onMounted } from "vue";

const emit = defineEmits(["update:modelValue"]);
const props = defineProps([
    "placeholder",
    "valid",
    "disable",
    "type",
    "last",
    "result",
    "preview",
    "content",
]);
const file = ref(props.content);
const previewUrl = ref(null);
const input = ref(null);

const changeFile = (event) => {
    var fileValue = event.target.files[0];
    if (fileValue) {
        var fileType = fileValue.type;
        if (fileValue.size / 1000000 > Number(5)) {
            alert(`File size cannot be more than ${5}mb`);
            event.target.value = "";
            file.value = "";
        } else {
            file.value = fileValue;
            emit("update:modelValue", fileValue);
        }
    }
};
const removeFile = () => {
    file.value = null;
    previewUrl.value = null;
    if (input.value) {
        input.value.value = "";
    }
    emit("update:modelValue", "");
};

const fileUrl = () => {
    if (typeof props.content === "object") {
        return URL.createObjectURL(file.value);
    }
    return props.content;
};

onMounted(() => {
    if (props.result) {
        file.value = props.result;
    }
    if (props.preview) {
        previewUrl.value = props.preview;
    }
});
</script>
