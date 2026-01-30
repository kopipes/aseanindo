<template>
    <div class="w-full mb-[-20px]">
        <p class="text-[14px] mb-2 font-krub-semibold">
            {{ title }}
        </p>
        <div v-if="search">
            <input
                type="text"
                placeholder="Search"
                class="bg-[#F2F4F5] w-full border shadow-none outline-none rounded-md px-3 py-2 text-[12px] mb-2 mt-[-4px]"
                v-model="searchItem"
            />
        </div>
        <ul
            class="flex flex-col gap-1 text-[13px] max-h-[60vh] overflow-auto mx-[-20px] px-[20px]"
        >
            <input
                type="radio"
                :name="name"
                value=""
                selected
                checked
                class="hidden"
            />
            <li v-for="item in itemList" x-data="{showSub : false}">
                <div class="flex justify-between items-center py-1 w-full cursor-pointer" x-on:click="showSub=!showSub">
                    <label
                        :for="createSlug(`${name}_${item.value}_${item.label}`)"
                        class="cursor-pointer flex-1"
                    >
                        {{ item.label }}
                    </label>
                    <input
                        type="radio"
                        :name="name"
                        class="radio-custom border-yellow cursor-pointer"
                        :id="createSlug(`${name}_${item.value}_${item.label}`)"
                        :value="item.value"
                        v-model="selectedItem"
                        v-if="!item.subs?.length"
                    />
                    <i class="isax icon-arrow-right-3 text-[14px] font-krub-bold" x-bind:class="{'rotate-90':showSub}" v-else>
                    </i>
                </div>
                <ul v-if="item.subs?.length" class="ps-[15px] w-full" x-show="showSub">
                    <li v-for="sub in item.subs" x-data="{showChild : false}">
                        <div class="flex justify-between items-center py-1" x-on:click="showChild=!showChild">
                            <label
                                :for="
                                    createSlug(
                                        `${name}_${sub.value}_${sub.label}`
                                    )
                                "
                                class="cursor-pointer flex-1"
                            >
                                {{ sub.label }}
                            </label>
                            <input
                                type="radio"
                                :name="name"
                                class="radio-custom border-yellow cursor-pointer"
                                :id="
                                    createSlug(
                                        `${name}_${sub.value}_${sub.label}`
                                    )
                                "
                                :value="sub.value"
                                v-model="selectedItem"
                                v-if="!sub.childs?.length"
                            />
                            <i class="isax icon-arrow-right-3 text-[14px] font-krub-bold" x-bind:class="{'rotate-90':showChild}" v-else>
                            </i>
                        </div>
                        <ul v-if="sub.childs?.length" class="ps-[15px] w-full" x-show="showChild">
                            <li v-for="child in sub.childs">
                                <div
                                    class="flex justify-between items-center py-1"
                                >
                                    <label
                                        :for="
                                            createSlug(
                                                `${name}_${child.value}_${child.label}`
                                            )
                                        "
                                        class="cursor-pointer flex-1"
                                    >
                                        {{ child.label }}
                                    </label>
                                    <input
                                        type="radio"
                                        :name="name"
                                        class="radio-custom border-yellow cursor-pointer"
                                        :id="
                                            createSlug(
                                                `${name}_${child.value}_${child.label}`
                                            )
                                        "
                                        :value="child.value"
                                        v-model="selectedItem"
                                    />
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        <button
            type="button"
            class="flex gap-4 items-center justify-center bg-yellow text-white text-[12px] font-krub-medium w-full py-2 rounded-md mt-2 mb-[-5px] disabled:bg-[#D9D9D9]"
            :disabled="!selectedItem || loading"
            @click="chooseItem"
        >
            Submit
            <IconLoading v-if="loading" />
        </button>
    </div>
</template>
<script setup lang="ts">
import IconLoading from "../../../Components/Icon/IconLoading.vue";
import { findNameByValueIterative } from "../../../libs/function";
import { ref, watch } from "vue";

const emit = defineEmits(["choose"]);
const props = defineProps(["items", "title", "name", "search", "value"]);

const itemList = ref(props.items);
const searchItem = ref("");
const selectedItem = ref(props.value);
const loading = ref(false);

const chooseItem = () => {
    loading.value = true;
    const itemId = selectedItem.value;
    const item = findNameByValueIterative(itemList.value, itemId);
    searchItem.value = " ";
    emit("choose", {
        value: itemId,
        label: item?.label,
        callback: () => {
            loading.value = false;
            document.getElementById("toggle-slide-popup").click();
        },
    });
};

const createSlug = (str: string, separator = "_") => {
    if (str) {
        return str
            .toString()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9 ]/g, "")
            .replace(/\s+/g, separator);
    }
    return str;
};

watch(
    () => props.items,
    (val, value) => {
        itemList.value = [];
        setTimeout(()=>{
            itemList.value = props.items;
        },100)
    }
);
watch(
    () => searchItem.value,
    (val, value) => {
        const searchVal = searchItem.value.toLowerCase();
        if (searchVal.replace(/ /g, "") != "") {
            itemList.value = props.items.filter(function (row) {
                return row.label.toLowerCase().includes(searchVal);
            });
        } else {
            itemList.value = props.items;
        }
    }
);

watch(
    () => props.value,
    (val, value) => {
        selectedItem.value = props.value;
    }
);
</script>
