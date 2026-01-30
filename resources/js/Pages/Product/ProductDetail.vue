<template>
    <SlideUpAnimation>
        <button
            type="button"
            class="flex items-center gap-2 px-4 py-4 text-[14px] font-krub-bold"
            @click="$emit('reset')"
        >
            <i class="isax icon-arrow-left-2 text-[17px]"></i>
            {{ product.name }}
        </button>
        <img
            :src="product.image"
            :alt="product.name"
            :title="product.name"
            class="object-cover bg-[#ddd] h-[100%] w-[100%]"
        />
        <div class="px-4 py-3">
            <div class="flex justify-between items-center mb-2">
                <p class="text-[13px] font-krub-semibold mt-[14px]">
                    <!-- {{ product.created_at }} -->
                    Description
                </p>
                <button
                    class="bg-yellow text-white w-[110px] text-[12px] rounded-md py-2 flex gap-2 items-center justify-center"
                    @click="call"
                    v-if="!product.faq_id"
                >
                    <IconContactUs/>
                    Contact Us
                </button>
                <button
                    class="bg-yellow text-white min-w-[110px] px-3 text-[12px] rounded-md py-2 flex gap-2 items-center justify-center"
                    @click="detailFaq(product.faq_id)"
                    v-else
                >
                    <IconInformation/>
                    More Information
                </button>
            </div>
            <p class="text-[13px] font-krub-regular text-justify pre-wrap-content">
                {{ product.description }}
            </p>
        </div>
    </SlideUpAnimation>
</template>
<script setup>
import SlideUpAnimation from "../../Components/SlideUpAnimation.vue";
import IconContactUs from "../../Components/Icon/IconContactUs.vue";
import IconInformation from "../../Components/Icon/IconInformation.vue";
import { useRouter } from "vue-router";
const router = useRouter();

const props = defineProps(["product"]);

const call = () => {
    router.push({
        name: "helpdesk-list",
        params: {
            id: props.product.id,
            category: "product",
        },
    });
};

const detailFaq = (id) => {
    router.push({
        name: "faq-detail",
        params: {
            id: id,
        },
    });
};
</script>
