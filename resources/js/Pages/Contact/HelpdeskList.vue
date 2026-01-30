<template>
    <SlideUpAnimation>
        <div class="px-6 py-4">
            <p class="flex items-center gap-2 text-[14px] font-krub-bold">
                How can we help you ?
            </p>
            <ul class="mt-4">
                <li v-for="n in 4" class="mb-2" v-if="loading">
                    <div
                        class="bg-[#dddddd80] rounded-full h-[50px]"
                        :key="n"
                    ></div>
                </li>
                <li v-for="row in helpdesk" class="mb-2" v-else>
                    <button
                        type="button"
                        @click="selectHelpdesk(row)"
                        class="text-[14px] py-4 text-yellow w-full flex items-center justify-center gap-1 bg-white font-krub-bold rounded-full border border-yellow disabled:cursor-not-allowed disabled:bg-[#C2C2C2] disabled:border-[#C2C2C2]"
                    >
                        {{ row.name }}
                    </button>
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

defineProps(["loading", "helpdesk"]);
const emit = defineEmits(["setSubHelpdesk"]);

const selectHelpdesk = (row) => {
    if (row.sub.length) {
        emit("setSubHelpdesk", row);
    } else {
        router.push({
            name: "helpdesk-list",
            params: {
                id: row.id,
                category: "helpdesk",
            },
        });
    }
};
</script>
