<template>
    <SlideUpAnimation>
        <div class="px-6 py-4">
            <button
                type="button"
                class="flex items-center gap-2 text-[15px] font-krub-bold mb-3"
                @click="$emit('reset')"
            >
                <i class="isax icon-arrow-left-2 text-[17px]"></i>
                {{ helpdesk.name }}
            </button>
            <p class="flex items-center gap-2 text-[14px] font-krub-bold">
                How can we help you ?
            </p>
            <ul class="mt-4">
                <li v-for="row in items" class="mb-2">
                    <ButtonYellow type="button" @click="selectHelpdesk(row.id)"  class="text-[12px] py-4">
                        {{ row.name }}
                    </ButtonYellow>
                </li>
            </ul>
        </div>
    </SlideUpAnimation>
</template>
<script setup>
import SlideUpAnimation from "../../Components/SlideUpAnimation.vue";
import ButtonYellow from "../../Components/Button/ButtonYellow.vue";
import { useRouter } from "vue-router";
const router = useRouter();
const props = defineProps(["items", "helpdesk"]);

const selectHelpdesk = (id) => {
    router.push({
        name: "helpdesk-list",
        params: {
            id: id,
            category: "helpdesk",
        },
        query: {
            history: props.helpdesk.id,
        },
    });
};
</script>
