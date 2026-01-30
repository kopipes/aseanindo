<template>
    <Main>
        <template v-slot:menu>
            <ProductContactMenu active="contact" />
        </template>
        <AgentList
            :loading="loading"
            :name="name"
            :agents="agentList"
            :category="category"
            :whatsAppUrl="whatsAppUrl"
            :singleAvatar="singleAvatar"
            :emailUrl="emailUrl"
            :id="id"
            @back="back"
            @findRandomAgent="findRandomAgent"
            v-if="agentList.length || loading || isAvailable"
        />
        <EmptyHelpdesk
            :officeHour="officeHour"
            :name="name"
            @back="back"
            v-else
        />
    </Main>
</template>
<script setup>
import Main from "../../Layout/Main.vue";
import ProductContactMenu from "../../Components/Menu/ProductContactMenu.vue";
import AgentList from "./AgentList.vue";
import EmptyHelpdesk from "./EmptyHelpdesk.vue";
import { useRouter, useRoute } from "vue-router";
import { api } from "../../Service/api-service.js";
import { useContext } from "../../Hooks/useContext";
import { ref, onBeforeMount, onMounted } from "vue";
import { socket } from "../../socket";

const router = useRouter();
const route = useRoute();
const context = useContext();

const loading = ref(true);
const name = ref("");
const officeHour = ref(null);
const agentList = ref([]);
const whatsAppUrl = ref(null)
const emailUrl = ref(null)
const isAvailable = ref(false)
const singleAvatar = ref(false)
const category = ref(route.params.category);
const id = ref(route.params.id);

const back = () => {
    const returnUrl = route.query.return_url
    if(returnUrl){
        router.go(-1)
    }else{
        var name = category.value === "product" ? "product-list" : "contact-list";
        router.push({
            name: name,
            query: {
                id: route.query.history || route.params.id,
            },
        });
    }
};

const fetchAgent = () => {
    const cookieLiveSource = context.cookie.get("live-source");
    if (category.value === "callback" && cookieLiveSource) {
        category.value = cookieLiveSource.category;
    }
    api.getListAgentHelpdesk(
        {
            id: id.value,
            category: category.value,
        },
        (result) => {
            name.value = result.name;
            officeHour.value = result.office_hours;
            agentList.value = result.agent;
            isAvailable.value = result.is_available
            whatsAppUrl.value = result.whatsAppUrl
            singleAvatar.value = result.singleAvatar
            emailUrl.value = result.emailUrl
            if (route.params.category === "callback" && cookieLiveSource) {
                // agentList.value = [];
            }
            loading.value = false;
        }
    );
};

const findRandomAgent = (callback) => {
    const cookieLiveSource = context.cookie.get("live-source");
    if (category.value === "callback" && cookieLiveSource) {
        category.value = cookieLiveSource.category;
    }
    api.findRandomAvailableAgent(
        {
            id: id.value,
            category: category.value,
        },
        (result) => {
            callback(result)
        }
    );
}
const listenSocketEvent = () => {
    socket.on("BROADCAST", (properties) => {
        const { channel, data } = properties;
        switch (channel) {
            case "refresh_agent":
                const companyId = data.company_id;
                const agentId = data.agent_id;
                if (
                    companyId === context.page.company.id &&
                    route.params.category !== "callback"
                ) {
                    // fetchAgent();
                    agentList.value = agentList.value.filter((row) => {
                        return row.user_id !== agentId;
                    });
                }
                break;
        }
    });
};

onBeforeMount(() => {
    fetchAgent();
});

onMounted(() => {
    listenSocketEvent();
});
</script>
