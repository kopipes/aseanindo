<template>
    <div>
        <div class="mt-1 mb-2 relative top-0 pt-2 bg-white">
            <i
                class="isax icon-search-normal-1 absolute left-[15px] top-[22px]"
            ></i>
            <input
                type="text"
                placeholder="Search"
                class="border bg-[#F5F7FC] outline-none w-full text-[13px] font-krub-medium px-4 py-3 rounded-md ps-[35px]"
                @input="$emit('update:modelValue', $event.target.value)"
            />
        </div>
        <div
            class="border rounded-md px-3 py-2 mb-4"
            v-if="!loading && topFaq.length"
        >
            <p class="text-[13px] mb-2">Pertanyaan yang sering di cari:</p>
            <ul class="flex gap-2 overflow-auto" v-if="!loading">
                <li v-for="top in topFaq">
                    <button
                        type="button"
                        class="bg-[#F5F7FC] border rounded-full px-3 py-1 whitespace-nowrap text-[13px]"
                        @click="$emit('selectFaq', top)"
                    >
                        {{ top.name }}
                    </button>
                </li>
            </ul>
            <!-- <ul class="flex gap-2 overflow-auto" v-else>
                <li v-for="n in 3">
                    <span
                        class="flex-1 bg-slate-200 animate-pulse h-[30px] w-[100px] rounded-md block"
                    ></span>
                </li>
            </ul> -->
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from "vue";
import { api } from "../../Service/api-service.js";

const topFaq = ref([]);
const loading = ref(true);

defineProps(["modelValue"]);
const fetchTopFaq = () => {
    loading.value = true;
    api.getTopFaq((result) => {
        topFaq.value = result;
        loading.value = false;
    });
};

onMounted(() => {
    fetchTopFaq();
});
</script>
