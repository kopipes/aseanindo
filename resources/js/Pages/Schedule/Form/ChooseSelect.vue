<template>
    <div>
        <p class="text-[13px] font-krub-semibold">{{ label }}</p>
        <button
            type="button"
            :disabled="loading || disable"
            @click="showListItem"
            class="bg-white border w-full flex justify-between items-center px-3 py-2 rounded-md text-[12px] font-krub-medium mt-[4px]"
        >
            {{ value || label }}
            <i class="isax icon-arrow-down-1" v-if="!loading"></i>
            <IconLoading v-else />
        </button>
    </div>
</template>
<script setup>
import IconLoading from "../../../Components/Icon/IconLoading.vue";
import { ref } from "vue";
const props = defineProps(["sender", "message", "disable", "value", "label"]);

const emit = defineEmits(["fetchItem"]);
const loading = ref(false);

const showListItem = () => {
    loading.value = true;
    emit("fetchItem", () => {
        loading.value = false;
    });
};
</script>
