<template>
    <Empty>
        <template v-slot:header>
            <div
                class="flex justify-between items-center bg-[#3943B7] border-b border-[#dddddd54] px-4 py-[5px] text-white"
            >
                <div
                    class="flex items-center gap-2 text-[12px] font-krub-semibold"
                >
                    <img
                        :src="agent.profile"
                        :alt="agent.user.name"
                        :title="agent.user.name"
                        width="40"
                        height="40"
                        class="object-cover rounded-full border-2 border-white h-[40px] w-[40px]"
                    />
                    <div class="flex flex-col">
                        <span class="text-[13px]">{{ agent.user.name }}</span>
                        <span
                            class="font-krub-medium text-[10px]"
                            v-if="call.state.status === 'ringing'"
                        >
                            Ringing ...
                        </span>
                        <span
                            class="font-krub-medium text-[12px] flex gap-1 items-center"
                            v-if="call.state.status === 'live'"
                        >
                            <span
                                class="border border-[#DC2926] w-[10px] h-[10px] rounded-full flex items-center justify-center"
                            >
                                <span
                                    class="block bg-[#DC2926] w-[4px] h-[4px] rounded-sm"
                                ></span>
                            </span>
                            {{ call.state.timer }}
                        </span>
                        <span @click="call.state.status=''" id="clear-call-status-state"></span>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <router-link
                        :to="{ name: 'product-list' }"
                        type="button"
                        v-if="chat.state.isDone"
                    >
                        <IconClose
                            class="w-[25px] h-[25px] border rounded-full"
                        />
                    </router-link>
                    <div class="flex gap-2" v-else>
                        <button
                            type="button"
                            @click="startCall"
                            v-if="!call.state.status && enableCall && isChatReplied()"
                        >
                            <IconCall class="w-[25px] h-[25px]" />
                        </button>
                        <div class="flex gap-2" v-if="call.state.status && enableCall">
                            <button
                                type="button"
                                @click="call.toggleMicrophone"
                            >
                                <IconMicMute
                                    class="w-[25px] h-[25px] border rounded-full"
                                    v-if="call.state.mute"
                                />
                                <IconMicCall
                                    class="w-[25px] h-[25px] border rounded-full"
                                    v-else
                                />
                            </button>
                            <button
                                type="button"
                                @click="setSlideSlot('end-call')"
                                x-on:click="slide=true"
                            >
                                <IconEndCall
                                    class="w-[25px] h-[25px] border rounded-full"
                                />
                            </button>
                        </div>
                        <button
                            type="button"
                            @click="setSlideSlot('end-chat')"
                            x-on:click="slide=true"
                        >
                            <IconEndChat
                                class="w-[25px] h-[25px] border rounded-full"
                            />
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <div class="bg-[#F8F9FA] h-full flex px-2 py-1 pb-0 items-end">
            <Conversation
                :conversation="chat.state.conversation"
                :user_id="chat.state.user_id"
                v-if="chat.state.conversation.length"
            />
        </div>
        <template v-slot:bottom>
            <div
                class="font-krub-medium text-[12px] text-center text-[#7c7c7c] py-2 px-3 bg-[#F8F9FA]"
                v-if="chat.state.isDone && chat.state.templateEndChat"
            >
                {{ chat.state.templateEndChat }}
            </div>
            <form
                @submit.prevent="sendMessage"
                class="border-t border-[#dddddd54] flex items-center justify-center"
                v-if="!chat.state.isDone"
            >
                <input
                    type="text"
                    class="outline-none px-3 py-3 flex-1 text-[13px] font-krub-regular"
                    placeholder="Type your message"
                    v-model="message"
                    maxlength="250"
                />
                <button
                    type="button"
                    class="px-1 h-full flex items-center justify-center"
                    @click="sendMyLocation"
                >
                    <IconMap class="w-[24px] h-[24px]" />
                </button>
                <button
                    type="button"
                    class="px-1 h-full flex items-center justify-center"
                    @click="inputFile.click()"
                >
                    <IconAttachment class="w-[20px] h-[20px]" />
                </button>
                <input
                    type="file"
                    ref="inputFile"
                    class="hidden"
                    @change="sendFile"
                />
                <button
                    type="submit"
                    class="px-3 h-full flex items-center justify-center ms-1"
                >
                    <SendMessage />
                </button>
            </form>
            <button type="button" @click="doShowRating" id="button-show-popup-rating-chat-trigger"></button>
            <button id="button-show-popup-rating-chat" type="button"  x-on:click="slide=true" class="hidden"></button>
        </template>

        <template v-slot:slide>
            <EndCall
                @confirm="call.endCall"
                v-if="slideSlot === 'end-call' && !call.state.showRating"
            />
            <EndChat
                @confirm="chat.endChat"
                v-if="slideSlot === 'end-chat' && !chat.state.showRating"
            />
            <FormRating
                @submit="chat.submitRating"
                source="chat"
                :agent="agent"
                v-if="chat.state.showRating"
            />
        </template>
    </Empty>
</template>
<script setup>
import Empty from "../../../Layout/Empty.vue";
import IconCall from "../../../Components/Icon/IconCall.vue";
import IconEndChat from "../../../Components/Icon/IconEndChat.vue";
import IconMicCall from "../../../Components/Icon/IconMicCall.vue";
import IconMicMute from "../../../Components/Icon/IconMicMute.vue";
import IconEndCall from "../../../Components/Icon/IconEndCall.vue";
import IconClose from "../../../Components/Icon/IconClose.vue";
import SendMessage from "../../../Components/Icon/SendMessage.vue";
import IconAttachment from "../../../Components/Icon/IconAttachment.vue";
import IconMap from "../../../Components/Icon/IconMap.vue";
import Conversation from "./Conversation.vue";
import FormRating from "../FormRating.vue";
import EndChat from "./EndChat.vue";
import EndCall from "../Call/EndCall.vue";
import { ref, onMounted } from "vue";
import { usePage } from "../../../Hooks/usePage";

const props = defineProps(["agent", "useChat", "useCall","agora"]);
const emit = defineEmits(["setSession"]);

const page = usePage()
const slideSlot = ref("");
const message = ref("");
const inputFile = ref(null);
const enableCall = ref(page.enable_call)

const chat = props.useChat;
const call = props.useCall;

const setSlideSlot = (value) => {
    slideSlot.value = value;
};

const doShowRating = ()=>{
    call.state.showRating = true;
    call.state.status = "";
}

const startCall = () => {
    if (!chat.state.chatId) {
        emit("setSession", "call");
    } else {
        if(!call.state.agora){
            call.setAgora(props.agora)
        }
        call.inviteAgent();
    }
};
const sendMessage = () => {
    var messageValue = message.value
    messageValue = messageValue.replaceAll(' ','')
    if (messageValue!='') {
        chat.send({
            message: message.value,
        });
        message.value = "";
    }
};

const sendMyLocation = () => {
    chat.send({
        type: "location",
    });
};

const sendFile = (event) => {
    var fileValue = event.target.files[0];
    if (fileValue) {
        chat.send({
            type: "file",
            message: fileValue,
        });
    }
};
const isChatReplied = () => {
    return chat.state.conversation.find((row)=>row.user_id!=null);
}
onMounted(() => {
    chat.startSession();
});
</script>
