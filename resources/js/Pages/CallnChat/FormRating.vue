<template>
    <form
        class="w-full px-4"
        x-data="{review:''}"
        @submit.prevent="submitRating"
    >
        <div class="flex items-center justify-center mb-4">
            <div
                class="flex flex-col items-center text-[10px] gap-1 user-helpdesk-item"
            >
                <div class="w-fit rounded-full p-[3px] bg-ig">
                    <img
                        :src="agent.profile"
                        :alt="agent.user.name"
                        :title="agent.user.name"
                        width="65"
                        height="65"
                        class="rounded-full object-cover border-[3px] border-white h-[65px] w-[65px]"
                    />
                </div>
                <span
                    class="whitespace-normal overflow-hidden line-clamp-1 truncate font-krub-bold text-[15px] text-[#2B2B2B]"
                >
                    {{ agent.user.name }}
                </span>
            </div>
        </div>
        <div class="overflow-auto max-h-[60vh] -mx-10 px-10">
            <ul class="flex flex-col gap-2">
                <li v-for="(text, index) in csat?.csat_items">
                    <p class="text-center text-[#0E0F0F] text-[14px] mb-1">
                        {{ text }}
                    </p>
                    <div class="flex gap-2 items-center justify-center">
                        <button
                            type="button"
                            v-for="star in 5"
                            @click="setCsatRating(index, star)"
                        >
                            <IconStar
                                class="w-[30px] h-[30px] cursor-pointer fill-[#C8CDD1]"
                                x-ref="content"
                                :data-rating="star"
                                v-bind:class="{
                                    'fill-yellow':
                                        csatRatings[index]?.rating >= star,
                                }"
                            />
                        </button>
                    </div>
                </li>
            </ul>
            <ul v-if="!csat">
                <li>
                    <p class="text-center text-[#0E0F0F] text-[14px] mb-3">
                        Silakan berikan penilaian atas pelayanan saya melalui
                        rating dibawah ini :
                    </p>
                    <div class="flex gap-2 items-center justify-center">
                        <button
                            type="button"
                            v-for="n in 5"
                            @click="setRating(n)"
                        >
                            <IconStar
                                class="w-[30px] h-[30px] cursor-pointer fill-[#C8CDD1]"
                                x-ref="content"
                                :data-rating="n"
                                v-bind:class="{ 'fill-yellow': rating >= n }"
                            />
                        </button>
                    </div>
                </li>
            </ul>
            <div v-if="!csat || csat?.free_text">
                <p class="text-center mt-3 mb-2 text-[#9B9B9B]">Your review:</p>
                <textarea
                    placeholder="Please write your review"
                    rows="5"
                    x-model="review"
                    maxlength="250"
                    v-model="review"
                    class="border outline-none border-yellow w-full resize-none rounded-lg px-3 py-2"
                ></textarea>
                <div
                    class="text-end text-[#C7CCD0] text-[12px] font-krub-regular"
                >
                    <span x-text="review.length"></span>/250
                </div>
            </div>
        </div>
        <ButtonYellow class="mt-3" type="submit" x-on:click="slide=false">
            Submit
        </ButtonYellow>
    </form>
</template>
<script setup>
import IconStar from "../../Components/Icon/IconStar.vue";
import ButtonYellow from "../../Components/Button/ButtonYellow.vue";
import { useContext } from "../../Hooks/useContext";
import { ref, onMounted, onBeforeUnmount } from "vue";

const rating = ref(0);
const review = ref("");
const emit = defineEmits(["submit"]);
const props = defineProps(["source", "agent"]);
const context = useContext();
const csat = ref(null);
const csatRatings = ref([]);

const setRating = (n) => {
    rating.value = n;
};
const setCsatRating = (index, rating) => {
    var ratingCsat = csatRatings.value;
    ratingCsat[index].rating = rating;
    csatRatings.value = ratingCsat;
};
const submitRating = () => {
    const csatValue = csat.value
    const isHasFreeText = csatValue || csatValue?.free_text
    var data = {
        review: review.value,
        rating: rating.value,
        source: props.source,
    }
    if(isHasFreeText){
        data.csat_rating = {
            has_free_text : isHasFreeText,
            ratings : csatRatings.value
        }
    }
    emit("submit",data);
};

const csatTemplateListener = () => {
    csat.value = null;
    csatRatings.value = [];
    const csatTemplate = context.cookie.get("CSAT-TEMPLATE");
    if (csatTemplate && csatTemplate?.has_csat) {
        csatRatings.value = csatTemplate.csat_items.map((text) => {
            return {
                rating: 0,
                text: text,
            };
        });
        csat.value = csatTemplate;
    }
};

onMounted(() => {
    csatTemplateListener();
    window.addEventListener("CSAT_TEMPLATE", csatTemplateListener);
});
onBeforeUnmount(() => {
    window.removeEventListener("CSAT_TEMPLATE", csatTemplateListener);
});
</script>
