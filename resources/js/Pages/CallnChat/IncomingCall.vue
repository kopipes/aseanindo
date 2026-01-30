<template>
    <div
        class="bg-yellow h-full w-[100vw] sm:min-w-callnchat sm:max-w-callnchat shadow-md flex flex-col relative"
        x-data="{loading:false,slide: false,alert:false }"
    >
        <Alert />
        <Loading class="loading incoming call"/>
        <div class="text-center py-5 font-krub-bold text-[18px]">
            Incoming call HaloYelow ...
        </div>
        <div
            class="w-full h-full flex flex-col items-center justify-center relative flex-1"
        >
            <p class="font-krub-bold mb-5 text-[23px]">
                {{ agent.user?.name }}
            </p>
            <div class="bg-[#FDE6AB] p-1 rounded-full">
                <img
                    :src="agent.profile"
                    :alt="agent.user.name"
                    :title="agent.user.name"
                    width="140"
                    height="140"
                    class="object-cover rounded-full border-[5px] border-white h-[140px] w-[140px]"
                />
            </div>
            <p class="font-krub-bold text-[18px] mt-5">
                {{ company.name }}
            </p>
        </div>
        <div class="py-10 w-full justify-center mb-10 flex gap-10">
            <button type="button" @click="declineCall">
                <IconDeclineCall class="w-[65px] h-[65px]" />
                <span class="block mt-2 font-krub-semibold text-[14px]">
                    Decline
                </span>
            </button>
            <button type="button" @click="accept">
                <IconAcceptCall class="w-[65px] h-[65px]" />
                <span class="block mt-2 font-krub-semibold text-[14px]">
                    Accept
                </span>
            </button>
        </div>
    </div>
</template>
<script setup>
import Alert from "../../Components/Popup/Alert.vue";
import Loading from "../../Components/Popup/Loading.vue";
import IconDeclineCall from "../../Components/Icon/IconDeclineCall.vue";
import IconAcceptCall from "../../Components/Icon/IconAcceptCall.vue";
import { useSound } from "../../Hooks/useSound";
import { useContext } from "../../Hooks/useContext";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { socket } from "../../socket";

const props = defineProps(["agent", "useCall"]);
const emit = defineEmits(["setSession"]);

const context = useContext();
const company = ref(context.page.company);
const sound = useSound();
const router = useRouter();

const call = props.useCall;

const accept = () => {
    const session = context.cookie.get("incoming-call");
    console.log(session)
    if (session) {
        context.loading.toggle();
        call.postTriggerCall(
            "accept",
            () => {
                context.loading.toggle();
                emit("setSession", "call");
                context.cookie.del("incoming-call");
                socket.emit("send-broadcast", {
                    clients: [context.cookie.get("call_id")],
                    channel: "call-accepted",
                    body: {
                        start_at : new Date(new Date().toString().split('GMT')[0]+' UTC').toISOString().match(/(\d{4}\-\d{2}\-\d{2})T(\d{2}:\d{2}:\d{2})/)
                    },
                });
                setTimeout(()=>{
                    call.state.agora.joinCall({
                        token: session.token,
                        channel: session.channel,
                        appId: context.base64decode(session.call_session),
                        userId: session.uuid.toString(),
                    });
                    sound.stopRinging();
                },200)
            },
            (error) => {
                sound.stopRinging();
                errorAction(error.response.data);
            }
        );
    }
};

const declineCall = () => {
    call.postTriggerCall(
        "reject",
        () => {
            context.cookie.del("incoming-call");
            context.cookie.del("call_id");
            sound.stopRinging();
        },
        (error) => {
            context.cookie.del("incoming-call");
            context.cookie.del("call_id");
            sound.stopRinging();
        }
    );
    router.push({
        name: "product-list",
    });
};

const errorAction = (message = null) => {
    sound.stopRinging();
    context.cookie.del("incoming-call");
    router.push({
        name: "product-list",
    });
    if (message) {
        setTimeout(() => {
            context.showAlert(message);
        }, 100);
    }
};
onMounted(() => {
    const session = context.cookie.get("incoming-call");
    if (session) {
        sound.ringing();
        call.listenSocketBroadcastEvent()
    }else{
        sound.stopRinging()
    }
});
</script>
