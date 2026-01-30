<template>
    <section class="pb-4">
        <ul v-if="loading">
            <li class="flex justify-between gap-2 mb-2" v-for="n in 5">
                <span class="flex-1 bg-slate-200 animate-pulse h-[25px] rounded-md"></span>
                <span
                    class="bg-slate-200 animate-pulse h-[25px] w-[15px] rounded-md"
                ></span>
            </li>
        </ul>
        <ul class="flex flex-col gap-2" v-else>
            <li v-for="faq in faqs">
                <button
                    type="button"
                    class="flex justify-between items-center text-[13px] font-krub-semibold py-2 w-full border rounded-md px-3 text-start"
                    @click="$emit('selectFaq',faq)"
                >
                    {{ faq.name }}
                    <ix
                        class="isax icon-arrow-right-3 duration-200 ease-out text-dark text-[18px]"
                        v-if="faq.subs_count"
                    ></ix>
                </button>
            </li>
        </ul>
    </section>
</template>
<script setup>
import { ref,watch, onMounted } from "vue";
import { api } from "../../Service/api-service.js";

const faqList = ref([]);
const faqs = ref([]);
const loading = ref(false);

const props = defineProps(['search'])

const fetchFaqList = () => {
    loading.value = true;
    api.getListFaq((result) => {
        faqList.value = result;
        faqs.value = result
        loading.value = false;
    });
};

const searchFaq = () => {
    const searchText = props.search.toLowerCase();
    const result =  filterNestedArray(faqList.value,searchText)
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

onMounted(() => {
    fetchFaqList();
});

watch(()=>props.search, (value, val) => {
    searchFaq();
});
</script>
