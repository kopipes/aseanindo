<template>
    <div class="h-full">
        <div v-if="agent && !loading" class="h-full">
            <IncomingCall
                :agent="agent"
                :useCall="call"
                @setSession="setSession"
                v-if="session === 'incoming-call'"
            />
            <Chat
                :agent="agent"
                :useChat="chat"
                :useCall="call"
                :agora="agora"
                @setSession="setSession"
                v-if="['chat', 'chat-call'].includes(session)"
            />
            <Call
                :agent="agent"
                :useCall="call"
                :useChat="chat"
                :agora="agora"
                @setSession="setSession"
                v-if="session === 'call'"
            />
        </div>
        <Loading v-if="loading" call="call n chat index" />
    </div>
</template>
<script setup>
import Loading from "./Loading.vue";
import Chat from "./Chat/Index.vue";
import Call from "./Call/Index.vue";
import IncomingCall from "./IncomingCall.vue";
import { socket } from "../../socket";
import { api } from "../../Service/api-service";
import { useRoute, useRouter } from "vue-router";
import { ref, onBeforeMount, onBeforeUnmount } from "vue";
import { useChat } from "../../Hooks/useChat";
import { useContext } from "../../Hooks/useContext";
import { useCall } from "../../Hooks/useCall";
import { useAgora } from "../../Hooks/useAgora";

const route = useRoute();
const router = useRouter();
const context = useContext();
const cookie = context.cookie;
const session = ref(route.query.category || "chat");
const agentId = ref(route.params.agent_id);
const loading = ref(false);
const agent = ref(null);
const call = ref(null);
const chat = ref(null);
const agora = ref(null)

const setSession = (value) => {
    session.value = value;
    if(agora.value && call.value){
        call.value.setAgora(agora.value);
    }
};

const fetchDetailAgent = () => {
    loading.value = true;
    cookie.del("ticket_id");
    api.getDetailAgent(agentId.value, (result) => {
        if (result) {
            loading.value = false;
            agent.value = result.agent;
            initCallChatSession(agent.value, result.template.end_chat);
            forceDisconnectCall();
            listenSession();
            cookie.set("template_end_chat", result.template.end_chat);
        } else {
            router.go(-1);
            setTimeout(() => {
                context.showAlert(
                    "The agent is not available now. Please try again later."
                );
                loading.value = false;
            }, 300);
        }
    });
};

const initCallChatSession = (agent, template) => {
    const chatHooks = useChat({
        destination: agent.user_id,
        agent_name: agent.user.name,
        router: router,
        template: template,
        setSession: setSession,
    });
    const callHooks = useCall({
        router: router,
        setSession: setSession,
    });
    chat.value = chatHooks
    agora.value = useAgora({
        call: callHooks,
    })
    callHooks.setAgora(agora.value);
    callHooks.setAgentId(agent.user_id);
    call.value = callHooks;
    listenSocketBroadcast(agent);
    chatHooks.listenSocketBroadcastEvent()
};

const listenSession = () => {
    if (cookie.get("chat_id")) {
        setTimeout(() => {
            session.value = "chat-call";
        }, 500);
    }
};

const forceDisconnectCall = () => {
    const callId = cookie.get("call_id");
    const incomingCall = cookie.get("incoming-call");
    if (callId && !incomingCall) {
        call.value.postTriggerCall("disconnect", () => {
            window.removeEventListener("beforeunload", confirmReload);
        });
        if (!cookie.get("chat_id")) {
            context.showAlert(
                "The agent is not available now. Please try again later."
            );
        }
    }
};
const listenPageReloaded = () => {
    context.pageReloadListener.listen();
};

const listenSocketBroadcast = (currentAgent) => {
    socket.on("BROADCAST", (properties) => {
        const { channel, data } = properties;
        switch (channel) {
            case "transfer-agent":
                var { agent, category } = data;
                if (chat.value) {
                    setTimeout(() => {
                        chat.value.transferChat(data);
                    }, 100);
                }
                currentAgent.user.id = agent.id;
                currentAgent.user.name = agent.name;
                currentAgent.profile = agent.profile;
                currentAgent.user_id = agent.id;
                agent.value = currentAgent;
                const callState = call.value;
                if (chat.value) {
                    chat.value.setDestination(agent.id, agent.name);
                }
                if (callState) {
                    callState.setAgentId(agent.id);
                    if (category === "chat" && callState) {
                        // end call
                        callState.state.agora?.leaveCall();
                        callState.endCall(false);
                    }
                }
                break;
            case "new-message":
                if (session.value === "call") {
                    session.value = "chat-call";
                }
                break;
        }
    });
};

onBeforeMount(() => {
    fetchDetailAgent();
    listenPageReloaded();
});
onBeforeUnmount(() => {
    context.pageReloadListener.destroy();
    if (call.value) {
        if (call.value.state.sound) call.value.state.sound.stopRinging();
        if (call.value.state.agora) {
            call.value.state.agora?.leaveCall();
        }
    }
});
</script>
