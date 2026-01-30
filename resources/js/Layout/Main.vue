<template>
    <div
        class="h-full w-[100vw] sm:min-w-callnchat sm:max-w-callnchat shadow-md flex flex-col relative bg-[#fff]"
        x-data="{loading:false,slide : false, alert:false }"
    >
        <Loading class="loading main"/>
        <Alert />
        <SlideOver>
            <slot name="slide" />
            <div class="slide-over w-full"></div>
        </SlideOver>
        <header class="text-center px-4 py-4 pb-3 bg-yellow text-white mb-2">
            <img
                :src="company.logo"
                :alt="company.profile.brand_name"
                width="80"
                height="80"
                class="rounded-full object-cover border p-1 inline h-[80px] w-[80px]"
            />
            <p class="text-[14px] font-krub-semibold text-white mt-2 flex w-full items-center justify-center gap-2">
                {{ company.profile.brand_name }}
                <IconVerified/>
            </p>
            <span
                class="text-white font-krub-medium text-[12px] block overflow-hidden"
            >
                {{ company.username }}
            </span>
            <a
                :href="
                    'https://maps.google.co.id/maps/search/' + company.profile.address
                "
                target="_blank"
                class="flex items-center justify-center"
            >
                <div
                    class="text-white text-[11px] font-krub-medium whitespace-normal overflow-hidden line-clamp-1 truncate"
                >
                    {{ company.profile.address }}
                </div>
            </a>
        </header>
        <slot name="menu" />
        <div class="flex-1 overflow-x-hidden overflow-y-auto">
            <slot />
        </div>
        <Footer />
    </div>
</template>
<script setup>
import IconMarker from "../Components/Icon/IconMarker.vue";
import Footer from "../Components/Footer.vue";
import Loading from "../Components/Popup/Loading.vue";
import SlideOver from "../Components/Popup/SlideOver.vue";
import Alert from "../Components/Popup/Alert.vue";
import IconVerified from "../Components/Icon/IconVerified.vue";
import { usePage } from "../Hooks/usePage.js";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";
import { useContext } from "../Hooks/useContext";

const page = usePage();
const router = useRouter();
const cookie = useContext().cookie;
const company = ref(page.company);

const isHaveLiveSession = () => {
    const callId = cookie.get("call_id");
    const chatId = cookie.get("chat_id");
    const liveSource = cookie.get("live-source");
    if (chatId && liveSource) {
        router.push({
            name: "live-session",
            params: {
                agent_id: liveSource.agent_id,
            },
            query: {
                category: "chat",
            },
        });
    }
    if (callId) {
        // Force end call
    }
};
onMounted(() => {
    isHaveLiveSession();
});
</script>
