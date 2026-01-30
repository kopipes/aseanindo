<template>
    <div class="w-full mb-[-20px]">
        <p class="text-[14px] mb-2 font-krub-semibold">
            {{ title || 'Daftar Produk' }}
        </p>
        <ul
            class="flex flex-col gap-2 text-[13px] max-h-[60vh] overflow-auto mx-[-20px] px-[20px]"
        >
            <li
                class="w-full flex justify-between cursor-pointer items-center"
                v-for="product in items"
            >
                <label
                    :for="`product_${product.id}`"
                    class="cursor-pointer flex-1"
                >
                    {{ product.name }}
                    <span v-if="['schedule_other','schedule_professional'].includes(product.category)">
                        <br>
                        {{ product.detail.location.join(',') }} : {{ product.detail.start_date }} - {{ product.detail.end_date }}, {{ product.detail.start_time }} - {{ product.detail.end_time }}
                    </span>
                </label>
                <input
                    type="radio"
                    name="product_id"
                    class="radio-custom"
                    :id="`product_${product.id}`"
                    :value="product.id"
                    v-model="selectedProduct"
                />
            </li>
        </ul>
        <button
            type="button"
            class="flex gap-4 items-center justify-center bg-yellow text-white text-[12px] font-krub-medium w-full py-2 rounded-md mt-2 mb-[-5px] disabled:bg-[#D9D9D9]"
            :disabled="!selectedProduct || loading"
            @click="chooseProduct"
        >
            Submit
            <IconLoading v-if="loading" />
        </button>
    </div>
</template>
<script setup>
import IconLoading from "../../Components/Icon/IconLoading.vue";
import { ref } from "vue";

const emit = defineEmits(["choose"]);
const props = defineProps(["items","title"]);

const selectedProduct = ref("");
const loading = ref(false);

const chooseProduct = () => {
    loading.value = true;
    const productId = selectedProduct.value
    const product = props.items.find((row)=>row.id==productId)
    emit("choose", {
        id: productId,
        name : product.name,
        callback: () => {
            loading.value = false;
            document.getElementById("toggle-slide-popup").click();
        },
    });
};
</script>
