<template>
    <SlideUpAnimation>
        <div class="px-4 py-4">
            <button
                type="button"
                class="flex items-center gap-2 text-[13px] font-krub-bold mb-3"
                @click="$emit('back')"
            >
                <i class="isax icon-arrow-left-2 text-[15px]"></i>
                <span
                    class="w-[130px] h-[10px] bg-[#dddddd65] rounded-full"
                    v-if="loading"
                ></span>
                <span v-else>{{ name }}</span>
            </button>
            <p class="flex items-center gap-2 text-[12px] font-krub-bold">
                Please click on one of our help desks to start a conversation
            </p>
            <ul
                class="mt-6 flex flex-wrap gap-3 items-center justify-center mx-4"
            >
                <li class="mb-2" v-for="n in 3" v-if="loading">
                    <button
                        type="button"
                        class="flex flex-col items-center text-[10px] gap-1 max-w-[70px]"
                    >
                        <div
                            class="rounded-full object-cover w-[54px] h-[54px] bg-[#dddddd65]"
                        ></div>
                        <span
                            class="rounded-lg w-[40px] bg-[#dddddd65] h-[6px] block"
                        >
                        </span>
                    </button>
                </li>
                <li class="mb-2" v-for="agent in agents" v-else>
                    <button
                        type="button"
                        class="flex flex-col items-center text-[10px] gap-1 max-w-[90px] user-helpdesk-item"
                        :data-id="agent.user_id"
                        :title="agent.user.name"
                        x-on:click="slide=true"
                        @click="selectAgent(agent.user_id)"
                    >
                        <div class="w-fit rounded-full p-[3px] bg-ig">
                            <img
                                :src="agent.user.profile"
                                :alt="agent.user.name"
                                width="65"
                                height="65"
                                class="rounded-full object-cover border-[3px] border-white h-[65px] w-[65px]"
                            />
                        </div>
                        <span
                            class="whitespace-normal overflow-hidden line-clamp-1 truncate"
                        >
                            {{ agent.user.name?.substring(0, 17) }}
                        </span>
                    </button>
                </li>
                <li class="mb-2" v-if="!loading && singleAvatar">
                    <button
                        type="button"
                        @click="liveRandomAgent"
                        class="flex flex-col items-center text-[10px] gap-1 max-w-[90px] user-helpdesk-item"
                    >
                        <div class="w-fit rounded-full p-[3px] bg-ig">
                            <img
                                :src="page.company?.logo"
                                alt="Live Agent"
                                width="65"
                                height="65"
                                class="rounded-full object-cover border-[3px] border-white h-[65px] w-[65px]"
                            />
                        </div>
                        <span
                            class="whitespace-normal overflow-hidden line-clamp-1 truncate"
                        >
                            Live Agent
                        </span>
                    </button>
                </li>
                <li class="mb-2" v-if="!loading && whatsAppUrl">
                    <a
                        :href="whatsAppUrl"
                        target="_blank"
                        class="flex flex-col items-center text-[10px] gap-1 max-w-[90px] user-helpdesk-item"
                    >
                        <IconLogoWa
                            class="rounded-full object-cover border-[3px] border-white h-[70px] w-[70px]"
                        />
                        <span
                            class="whitespace-normal overflow-hidden line-clamp-1 truncate"
                        >
                            Whatsapp
                        </span>
                    </a>
                </li>
                <li class="mb-2" v-if="!loading && emailUrl">
                    <a
                        :href="emailUrl"
                        class="flex flex-col items-center text-[10px] gap-1 max-w-[90px] user-helpdesk-item"
                    >
                        <div class="w-fit rounded-full p-[3px] bg-ig">
                            <IconLogoEmail
                                class="rounded-full object-cover border-[3px] border-white bg-white h-[70px] w-[70px]"
                            />
                        </div>
                        <span
                            class="whitespace-normal overflow-hidden line-clamp-1 truncate"
                        >
                            Email
                        </span>
                    </a>
                </li>
            </ul>
            <button id="trigger-slide-button" x-on:click="slide=true"></button>
        </div>
        <Teleport to=".slide-over" v-if="showSlideOver">
            <ButtonYellow
                type="button"
                class="mb-2 font-krub-semibold text-[12px]"
                @click="startLiveSession('call')"
                v-if="enableCall"
            >
                <IconCallButton class="w-[20px] h-[20px] me-2" />
                Call
            </ButtonYellow>
            <ButtonYellow
                type="button"
                class="font-krub-semibold text-[12px]"
                @click="startLiveSession('chat')"
                v-if="!enableCall"
            >
                <IconChatButton class="w-[20px] h-[20px] me-2" />
                Chat
            </ButtonYellow>
        </Teleport>
    </SlideUpAnimation>
</template>
<script setup>
import SlideUpAnimation from "../../Components/SlideUpAnimation.vue";
import ButtonYellow from "../../Components/Button/ButtonYellow.vue";
import IconCallButton from "../../Components/Icon/IconCallButton.vue";
import IconChatButton from "../../Components/Icon/IconChatButton.vue";
import IconLogoWa from "../../Components/Icon/IconLogoWa.vue";
import IconLogoEmail from "../../Components/Icon/IconLogoEmail.vue";
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useContext } from "../../Hooks/useContext";
import { usePage } from "../../Hooks/usePage";

const props = defineProps([
    "loading",
    "name",
    "agents",
    "category",
    "id",
    "whatsAppUrl",
    "singleAvatar",
    "emailUrl",
]);
const emit = defineEmits(["back", "findRandomAgent"]);

const page = usePage();
const router = useRouter();
const route = useRoute();
const context = useContext();
const cookie = context.cookie;
const showSlideOver = ref(false);
const agentId = ref(null);
const enableCall = ref(page.enable_call);

const selectAgent = (id) => {
    showSlideOver.value = true;
    agentId.value = id;
};
const liveRandomAgent = () => {
    context.loading.toggle();
    emit("findRandomAgent", (data) => {
        context.loading.hide();
        showSlideOver.value = true;
        agentId.value = data.user_id;
        document.getElementById("trigger-slide-button").click();
    });
};
const startLiveSession = (category) => {
    if (agentId.value) {
        router.push({
            name: "live-session",
            params: {
                agent_id: agentId.value,
            },
            query: {
                category,
            },
        });
        cookie.set("live-source", {
            id: props.id,
            category: props.category,
            agent_id: agentId.value,
        });
    }
};

const handleTriggerUser = () => {
    const trigger = route.query.trigger;
    if (trigger) {
        setTimeout(() => {
            document
                .querySelector(`.user-helpdesk-item[data-id="${trigger}"]`)
                ?.click();
        }, 1000);
    }
};

onMounted(() => {
    handleTriggerUser();
});
</script>
