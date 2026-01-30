<template>
    <div>
        <div class="px-3 py-2">
            <div class="mt-2 mb-2 relative">
                <i
                    class="isax icon-search-normal-1 absolute left-[15px] top-[15px]"
                ></i>
                <input
                    type="text"
                    placeholder="Search"
                    class="border bg-[#F5F7FC] outline-none w-full text-[13px] font-krub-medium px-4 py-3 rounded-md ps-[35px]"
                    v-model="search"
                />
            </div>
            <div class="border rounded-md px-3 py-2 mb-4" v-if="topFaq.length">
                <p class="text-[13px] mb-2">Pertanyaan yang sering di cari:</p>
                <ul class="flex gap-2 overflow-auto">
                    <li v-for="top in topFaq">
                        <button
                            type="button"
                            class="bg-[#F5F7FC] border rounded-full px-3 py-1 whitespace-nowrap text-[15px]"
                            @click="$emit('select', top)"
                        >
                            {{ top.name }}
                        </button>
                    </li>
                </ul>
            </div>
            <ul
                class="flex flex-col gap-2"
                x-data="{ 
                    activeAccordion: '', 
                    setActiveAccordion(id) { 
                        this.activeAccordion = (this.activeAccordion == id) ? '' : id 
                    } 
                }"
            >
                <li v-for="faq in faqs">
                    <button
                        type="button"
                        class="flex justify-between items-center text-[16px] font-krub-semibold py-2 w-full border rounded-md px-3"
                        @click="$emit('select', faq)"
                        v-if="!faq.sub.length"
                    >
                        {{ faq.name }}
                    </button>
                    <div
                        class="text-[16px] font-krub-semibold py-2 w-full border rounded-md px-3"
                        v-else
                        x-data="{ id: $id('accordion') }"
                    >
                        <button
                            type="button"
                            x-on:click="setActiveAccordion(id)"
                            class="flex w-full justify-between items-center"
                        >
                            {{ faq.name }}
                            <i
                                class="isax icon-arrow-right-3 duration-200 ease-out text-dark text-[20px]"
                                x-bind:class="{ 'rotate-90': activeAccordion==id }"
                            ></i>
                        </button>
                        <ul
                            class="flex flex-col gap-2 mt-2"
                            x-show="activeAccordion==id"
                            x-data="{ 
                                activeChild: '', 
                                setActiveChild(id) { 
                                    this.activeChild = (this.activeChild == id) ? '' : id 
                                } 
                            }"
                        >
                            <li v-for="sub in faq.sub">
                                <button
                                    type="button"
                                    class="flex justify-between items-center text-[16px] font-krub-semibold py-2 w-full border rounded-md px-3"
                                    @click="$emit('select', sub)"
                                    v-if="!sub.child.length"
                                >
                                    {{ sub.name }}
                                </button>
                                <div
                                    class="text-[16px] font-krub-semibold py-2 w-full border rounded-md px-3"
                                    v-else
                                    x-data="{ id: $id('accordion') }"
                                >
                                    <button
                                        type="button"
                                        x-on:click="setActiveChild(id)"
                                        class="flex w-full justify-between items-center"
                                    >
                                        {{ sub.name }}
                                        <i
                                            class="isax icon-arrow-right-3 duration-200 ease-out text-dark text-[20px]"
                                            x-bind:class="{ 'rotate-90': activeChild==id }"
                                        ></i>
                                    </button>
                                    <ul
                                        class="flex flex-col gap-2 mt-2"
                                        x-show="activeChild==id"
                                    >
                                        <li v-for="child in sub.child">
                                            <button
                                                type="button"
                                                class="flex justify-between items-center text-[16px] font-krub-semibold py-2 w-full border rounded-md px-3"
                                                @click="$emit('select', child)"
                                            >
                                                {{ child.name }}
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <ul v-if="loading">
                <li class="flex justify-between gap-2 mb-2" v-for="n in 5">
                    <span
                        class="flex-1 bg-[#dddddd48] h-[25px] rounded-md"
                    ></span>
                    <span
                        class="bg-[#dddddd48] h-[25px] w-[15px] rounded-md"
                    ></span>
                </li>
            </ul>
        </div>
        <div
            class="flex justify-center items-center flex-1 mt-[100px]"
            v-if="!faqs.length && !loading"
        >
            <EmptyState />
        </div>
    </div>
</template>
<script setup>
import EmptyState from "../../Components/Icon/EmptyState.vue";
import { ref, watch } from "vue";
const props = defineProps(["items", "loading", "topFaq"]);
const faqs = ref(props.items);
const search = ref("");

const searchFaq = () => {
    const searchText = search.value.toLowerCase();
    const result =  filterNestedArray(props.items,searchText)
    faqs.value = result;
};

const filterNestedArray = (arr,search) =>{
    return arr.filter(item => {
        const matches = item.name.toLowerCase().includes(search) || item.content.toLowerCase().includes(search);
        if (item.sub && item.sub.length > 0) {
            item.sub = filterNestedArray(item.sub, search);
        }

        if (item.child && item.child.length > 0) {
            item.child = filterNestedArray(item.child, search);
        }
        return matches || (item.sub && item.sub.length > 0) || (item.child && item.child.length > 0);
    });
}
watch(search, (value, val) => {
    searchFaq();
});
watch(
    () => props.items,
    (items, value) => {
        faqs.value = props.items;
    }
);
</script>
