<template>
    <Empty>
        <template v-slot:header>
            <div
                class="flex justify-between items-center bg-[#3943B7] border-b border-[#dddddd54] px-4 py-[5px] text-white"
            >
                <button
                    type="button"
                    class="flex items-center gap-2"
                    x-on:click="endConfirmation=true"
                >
                    <i class="isax icon-arrow-left-2 text-[20px]"></i>
                    <img
                        :src="company.logo"
                        :alt="company.profile.brand_name"
                        width="38"
                        height="38"
                        class="object-cover rounded-full border-2 border-white h-[38px] w-[38px]"
                    />
                    <span class="font-krub-semibold text-[13px]">
                        {{ company.profile.brand_name }}
                    </span>
                </button>
            </div>
        </template>
        <div class="bg-[#F8F9FA] h-full flex px-2 py-1 pb-0 items-end">
            <Conversation
                :company="company"
                :conversation="bot.state.conversation"
                :thinking="bot.state.thinking"
                :phones="phoneCode"
                @sendMessage="sendMessageOption"
                @sendBotResponse="sendBotResponse"
                @fetchProduct="fetchProduct"
                @resendBookingOtp="resendBookingOtp"
                @validateBookingOtp="validateBookingOtp"
                @setTitleListProduct="setTitleListProduct"
                @showSlideSubject="showSlideSubject"
                @postNextMessage="postNextMessage"
            />
        </div>
        <template v-slot:bottom>
            <form
                @submit.prevent="sendMessage"
                class="border-t border-[#ddd] border-b flex items-center justify-center"
            >
                <input
                    type="text"
                    class="outline-none px-3 py-3 flex-1 text-[13px] font-krub-regular"
                    placeholder="Type your message"
                    v-model="message"
                    maxlength="250"
                />
                <button
                    type="submit"
                    class="px-3 h-full flex items-center justify-center ms-1"
                >
                    <SendMessage />
                </button>
            </form>
        </template>

        <button
            type="button"
            class="hidden"
            id="toggle-slide-popup"
            x-on:click="slide=!slide"
        ></button>
        <template v-slot:slide>
            <ProductList
                :title="titleListProduct"
                :items="productList"
                @choose="chooseProduct"
                v-if="slideType == 'product'"
            />
            <SubjectList
                :items="subjectList"
                @choose="chooseSubject"
                v-if="slideType == 'subject'"
            />
        </template>
        <EndConfirmation @confirm="endSession" />
    </Empty>
</template>
<script setup>
import Empty from "../../Layout/Empty.vue";
import SendMessage from "../../Components/Icon/SendMessage.vue";
import Conversation from "./Conversation.vue";
import ProductList from "./ProductList.vue";
import SubjectList from "./SubjectList.vue";
import EndConfirmation from "./EndConfirmation.vue";
import { ref, onMounted } from "vue";
import { usePage } from "../../Hooks/usePage";
import { useChatBot } from "../../Hooks/useChatBot";
import { useRouter } from "vue-router";

const page = usePage();
const bot = useChatBot();
const router = useRouter();
const hasChatBotFlow = page.has_chatbot_flow && page.has_chatbot;

const company = ref(page.company);
const message = ref("");
const productList = ref([]);
const subjectList = ref([]);
const phoneCode = ref([]);
const titleListProduct = ref("");
const slideType = ref("product");

const sendMessage = () => {
    if (message.value) {
        bot.userSendMessage(message.value);
        message.value = "";
    }
};

const sendMessageOption = (properties) => {
    bot.userSendMessage(
        properties.message,
        properties.type || "message",
        properties.callback,
        properties.error
    );
};

const startBotSession = () => {
    if (hasChatBotFlow) {
        bot.startSession({
            errorCallback: () => {
                window.location.href = `${page.app_url}/contact`;
            },
        });
    } else {
        router.push({
            name: "contact-list",
        });
    }
};

const sendBotResponse = (properties) => {
    bot.botResponseMessage(
        properties.message,
        properties.type || "message",
        properties.callback,
        properties.error
    );
};

const fetchProduct = (callback) => {
    bot.fetchProductList((data) => {
        productList.value = data;
        document.getElementById("toggle-slide-popup").click();
        callback();
    });
};

const chooseSubject = (properties) => {
    bot.userSendMessage(properties.message, "message", properties.callback,null,{
        subject_id : properties.id
    });
};
const chooseProduct = (properties) => {
    bot.userSendMessage(properties.id, "product", properties.callback);
};
const fetchPhoneCountry = () => {
    bot.fetchPhoneCountry((data) => {
        phoneCode.value = data;
    });
};

const resendBookingOtp = (callback) => {
    bot.resendBookingOtp(callback);
};
const validateBookingOtp = (props) => {
    bot.validateBookingOtp(props);
};

const endSession = () => {
    bot.endBotSession(() => {
        router.push({
            name: "contact-list",
        });
    });
};
const showSlideSubject = (options) => {
    slideType.value = "subject";
    subjectList.value = options;
    document.getElementById("toggle-slide-popup").click();
};
const setTitleListProduct = (title) => {
    slideType.value = "product";
    titleListProduct.value = title;
};
const postNextMessage = () => {
    bot.postNextMessage()
}
onMounted(() => {
    startBotSession();
    fetchPhoneCountry();
});
</script>
