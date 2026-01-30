<template>
    <RouterView  :key="$route.fullPath"/>
</template>
<script setup>
import { RouterView, useRouter } from "vue-router";
import { socket } from "../socket";
import { onMounted } from "vue";
import { useContext } from "../Hooks/useContext";

const router = useRouter();
const cookie = useContext().cookie;
const handleBroadcastEvent = () => {
    socket.on("BROADCAST", (properties) => {
        const { channel, data } = properties;
        switch (channel) {
            case "user-login":
                cookie.del('authentication')
                window.location.reload()
                break;
            case "incoming-call":
                handleIncomingCall(data);
                break;
            case "new-message":
                handleIncomingChat(data);
                break;
        }
    });
};

const handleIncomingCall = (data) => {
    console.log(data)
    cookie.set("call_id", data.call_id);
    cookie.set("incoming-call", {
        call_id: data.call_id,
        token: data.call_token,
        uuid: data.call_uuid,
        channel: data.channel_name,
        call_session: data.call_session,
    });
    router.push({
        name: "live-session",
        params: {
            agent_id: data.user.id,
        },
        query: {
            category: "incoming-call",
        },
    });
};

const handleIncomingChat = (data) => {
    if (!cookie.get("chat_id")) {
        cookie.set("chat_id", data.chat_id);
        
        router.push({
            name: "live-session",
            params: {
                agent_id: data.user_id,
            },
            query: {
                category: "chat",
            },
        });
    }
};
onMounted(() => {
    handleBroadcastEvent();
});
</script>
