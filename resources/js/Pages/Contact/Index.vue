<template>
    <Main>
        <template v-slot:menu>
            <ProductContactMenu active="contact" />
        </template>

        <div v-if="!hasChatBotFlow">
            <HelpdeskList
                :loading="loading"
                :helpdesk="listHelpdesk"
                @setSubHelpdesk="setSubHelpdesk"
                v-if="!helpdesk && !subHelpdesk.length"
            />
            <SubHelpdesk
                :items="subHelpdesk"
                :helpdesk="helpdesk"
                @reset="reset"
                v-else
            />
        </div>
        <Bot v-else/>
    </Main>
</template>
<script setup>
import Main from "../../Layout/Main.vue";
import ProductContactMenu from "../../Components/Menu/ProductContactMenu.vue";
import HelpdeskList from "./HelpdeskList.vue";
import SubHelpdesk from "./SubHelpdesk.vue";
import Bot from "./Bot.vue";
import { api } from "../../Service/api-service.js";
import { ref, onBeforeMount } from "vue";
import { useRouter, useRoute } from "vue-router";
import { usePage } from "../../Hooks/usePage";

const page = usePage();
const router = useRouter();
const route = useRoute();
const hasChatBotFlow = ref(page.has_chatbot_flow && page.has_chatbot);

const subHelpdesk = ref([]);
const loading = ref(true);
const listHelpdesk = ref([]);
const helpdesk = ref(null);

const setSubHelpdesk = (row) => {
    helpdesk.value = {
        name: row.name,
        id: row.id,
    };
    subHelpdesk.value = row.sub;
};

const reset = () => {
    subHelpdesk.value = false;
    helpdesk.value = false;
    resetIdParam();
};

const fetchHelpdeskList = () => {
    api.getListHelpdesk((result) => {
        listHelpdesk.value = result;
        loading.value = false;
        hasQueryParamId();
    });
};

const hasQueryParamId = () => {
    const id = route.query.id;
    if (id) {
        const selected = listHelpdesk.value.find((row) => row.id == id);
        if (selected && selected.sub.length) {
            setSubHelpdesk(selected);
        }
    }
};
const resetIdParam = () => {
    const id = route.query.id;
    if (id) {
        router.replace({ query: null });
    }
};
onBeforeMount(() => {
    fetchHelpdeskList();
});
</script>
