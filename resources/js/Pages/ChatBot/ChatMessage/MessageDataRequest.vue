<template>
    <div class="max-w-[70%] w-full">
        <div class="px-2 py-1 pb-2 rounded-lg border bg-[#F2F4F5] w-full">
            <div
                class="flex justify-between text-[10px] font-krub-regular mb-1 gap-3"
            >
                <span>
                    {{ sender }}
                </span>
                <span>
                    {{ message.time }}
                </span>
            </div>
            <p
                class="font-krub-medium text-[11px] chat-message-content"
                v-html="message.message.message"
            ></p>
            <button
                type="button"
                @click="goToForm(message.message.properties.products_id)"
                class="bg-yellow text-white w-full text-[11px] rounded-md py-[6px] px-3 mt-2"
            >
                {{ message.message.properties.menu_title || "Daftar Produk" }}
            </button>
            <!-- <button
                type="button"
                class="flex border-t-2 border-white mt-1 text-[11px] font-krub-semibold items-center justify-center gap-2 text-[#0168A5] w-full pt-2 disabled:text-[#D9D9D9]"
                :disabled="disable || loading"
                @click="showListProduct"
            >
                <i class="isax icon-textalign-justifycenter"></i>
                {{ message.message.properties.menu_title || "Daftar Produk" }}
                <IconLoading v-if="loading"/>
            </button> -->
        </div>
    </div>
</template>
<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
const props = defineProps(["sender", "message", "disable"]);

const emit = defineEmits([
    "fetchProduct",
    "setTitleListProduct",
    "postNextMessage",
]);
const loading = ref(false);
const router = useRouter();

const goToForm = (products_id) => {
    const PRODUCT_JSON = btoa(products_id)
        .replace(/\+/g, "-")
        .replace(/\//g, "_")
        .replace(/=+$/, "");
    const PRODUCT_TYPE = props.message.message.properties.product_type;
    const ROUTE = {
        general: "schedule-general-product",
        schedule_professional: "schedule-professional-product",
        schedule_other: "schedule-other-product",
    };
    const routeData = router.resolve({ 
        name: ROUTE[PRODUCT_TYPE] ,
        params : {
            product_json : PRODUCT_JSON
        }
    });
    window.open(routeData.href, "_blank");
    emit("postNextMessage");
};
const showListProduct = () => {
    loading.value = true;
    emit("setTitleListProduct", props.message.message.properties.menu_title);
    emit("fetchProduct", () => {
        loading.value = false;
    });
};
</script>
