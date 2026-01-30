<template>
    <SlideUpAnimation>
        <button
            type="button"
            class="flex items-center gap-2 px-4 py-4 text-[14px] font-krub-bold text-start"
            @click="$emit('close')"
        >
            <i class="isax icon-arrow-left-2 text-[17px]"></i>
            {{ faq?.name }}
        </button>

        <div class="px-4">
            <p
                class="text-[13px] font-krub-regular text-justify chat-message-content faq-content bg-[#F5F7FC] px-2 py-2 rounded-md"
                v-html="faq?.description"
            ></p>
        </div>
        <div class="sticky bottom-0 bg-white py-3 flex justify-center" v-if="faq.product_id">
            <button
                class="bg-yellow text-white w-[110px] text-[12px] rounded-md py-2 flex gap-2 items-center justify-center"
                @click="call"
            >
                <IconContactUs/>
                Contact Us
            </button>
        </div>
    </SlideUpAnimation>
</template>
<script setup>
import SlideUpAnimation from "../../Components/SlideUpAnimation.vue";
import IconContactUs from "../../Components/Icon/IconContactUs.vue";
import { useRouter } from "vue-router";
const router = useRouter();

const props = defineProps(["faq"]);

const call = () => {
    router.push({
        name: "helpdesk-list",
        params: {
            id: props.faq.product_id,
            category: "product",
        },
    });
};
</script>
