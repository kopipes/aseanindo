<template>
    <Empty>
        <div
            class="w-full h-full flex flex-col items-center justify-center relative"
        >
            <div class="bg-ig p-1 rounded-full">
                <img
                    :src="agent.profile"
                    :alt="agent.user.name"
                    :title="agent.user.name"
                    width="140"
                    height="140"
                    class="object-cover rounded-full border-[5px] border-white h-[140px] w-[140px]"
                />
            </div>
            <p class="font-krub-bold mt-3">
                {{ agent.user?.name }}
            </p>
            <p class="font-krub-medium text-[14px] my-1">
                {{ company.name }}
            </p>
            <p class="font-krub-bold" v-if="call.state.status === 'ringing'">
                Ringing . . .
            </p>
            <p class="font-krub-bold" v-if="call.state.status === 'busy'">
                Busy
            </p>
            <div
                v-if="call.state.status === 'live'"
                class="font-krub-medium text-[13px] mt-4 flex items-center justify-center gap-2"
            >
                <span
                    class="border border-[#DC2926] w-[15px] h-[15px] rounded-full flex items-center justify-center"
                >
                    <span
                        class="block bg-[#DC2926] w-[6px] h-[6px] rounded-sm"
                    ></span>
                </span>
                {{ call.state.timer }}
            </div>

            <div
                v-if="call.state.status === 'live'"
                class="bg-[#FFF9EA] border border-yellow rounded-md flex items-center text-[10px] font-krub-medium gap-2 px-3 py-1 bottom-5 absolute"
            >
                <IconRecord />
                Dear customer, Please note that your call is being recorded.
            </div>
            <button
                type="button"
                @click="doShowRating"
                id="button-show-popup-rating-trigger"
            ></button>
        </div>

        <template v-slot:bottom>
            <div
                class="bg-[#FFECCC] rounded-t-2xl flex items-center justify-center py-5 gap-3"
            >
                <button type="button" @click="call.toggleMicrophone">
                    <IconMicCall
                        class="w-[45px] h-[45px]"
                        v-if="!call.state.mute"
                    />
                    <IconMicMute class="w-[45px] h-[45px]" v-else />
                </button>
                <button
                    type="button"
                    @click="$emit('setSession', 'chat-call')"
                    v-if="call.state.status === 'live'"
                >
                    <IconChatCall class="w-[45px] h-[45px]" />
                </button>
                <button type="button" x-on:click="slide=true">
                    <IconEndCall class="w-[45px] h-[45px]" />
                </button>
                <button
                    id="button-show-popup-rating"
                    type="button"
                    x-on:click="slide=true"
                    class="hidden"
                ></button>
            </div>
        </template>

        <template v-slot:slide>
            <EndCall @confirm="call.endCall" v-if="!call.state.showRating" />
            <FormRating
                @submit="call.submitRating"
                source="call"
                :agent="agent"
                v-else
            />
        </template>
    </Empty>
</template>
<script setup>
import Empty from "../../../Layout/Empty.vue";
import IconMicCall from "../../../Components/Icon/IconMicCall.vue";
import IconMicMute from "../../../Components/Icon/IconMicMute.vue";
import IconEndCall from "../../../Components/Icon/IconEndCall.vue";
import IconChatCall from "../../../Components/Icon/IconChatCall.vue";
import IconRecord from "../../../Components/Icon/IconRecord.vue";
import FormRating from "../FormRating.vue";
import EndCall from "./EndCall.vue";
import { usePage } from "../../../Hooks/usePage";
import { useSound } from "../../../Hooks/useSound";
import { ref, onMounted } from "vue";

const props = defineProps(["agent", "useCall", "useChat", "agora"]);

const page = usePage();
const call = props.useCall;
// const chat = props.useChat;

const company = ref(page.company);

const doShowRating = () => {
    call.state.showRating = true;
    call.state.status = "";
};

onMounted(() => {
    setTimeout(() => {
        const sound = useSound();
        if (!call.state.status) {
            sound.ringing();
            if (!call.state.agora) {
                call.setAgora(props.agora);
            }
            call.inviteAgent();
        } else {
            sound.stopRinging();
        }
    }, 500);
});
</script>
