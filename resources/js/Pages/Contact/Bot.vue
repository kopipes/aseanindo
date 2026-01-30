<template>
    <SlideUpAnimation>
        <div class="px-6 py-4">
            <p class="flex items-center gap-2 text-[14px] font-krub-bold">
                How can we help you ?
            </p>
            <button
                type="button"
                @click="starBot"
                class="flex justify-between border rounded-lg w-full px-4 py-3 text-start items-center mt-2"
            >
                <div>
                    <p class="font-krub-bold text-[14px]">Send us a message</p>
                    <p class="font-krub-medium text-[13px]">
                        We will respond to you soon
                    </p>
                </div>
                <div>
                    <IconSendYelow class="w-[25px] h-[25px]" />
                </div>
            </button>
        </div>
    </SlideUpAnimation>
</template>
<script setup>
import SlideUpAnimation from "../../Components/SlideUpAnimation.vue";
import IconSendYelow from "../../Components/Icon/IconSendYelow.vue";
import { useRouter } from "vue-router";
import { useContext } from "../../Hooks/useContext";

const router = useRouter();
const context = useContext();

const starBot = () => {
    context.cookie.del("chat_bot_session");
    router.push({
        name: "bot-start",
    });
};
</script>
