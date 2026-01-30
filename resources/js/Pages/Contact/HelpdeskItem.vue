<template>
    <Main>
        <template v-slot:menu>
            <ProductContactMenu active="contact" />
        </template>
        <div class="px-6 py-4">
            <p class="flex items-center gap-2 text-[14px] font-krub-bold">
                How can we help you ?
            </p>
            <ul class="mt-4">
                <li v-for="n in 4" class="mb-2" v-if="loading">
                    <div
                        class="bg-[#dddddd80] rounded-full h-[50px]"
                        :key="n"
                    ></div>
                </li>
                <li v-for="row in helpdesk" class="mb-2" v-else>
                    <ButtonYellow
                        type="button"
                        @click="selectHelpdesk(row)"
                        class="text-[12px] py-4"
                    >
                        {{ row.name }}
                    </ButtonYellow>
                </li>
            </ul>
        </div>
    </Main>
</template>
<script setup>
import Main from "../../Layout/Main.vue";
import ProductContactMenu from "../../Components/Menu/ProductContactMenu.vue";
import ButtonYellow from "../../Components/Button/ButtonYellow.vue";
import { useRouter, useRoute } from "vue-router";
import { onMounted, ref } from "vue";
import { api } from "../../Service/api-service.js";

const router = useRouter();
const route = useRoute();
const helpdesk = ref([]);
const loading = ref(false);

const fetchHelpdeskList = () => {
    loading.value = true;
    var helpdeskId = route.query["id[]"];
    if(typeof helpdeskId==='string'){
        helpdeskId = [helpdeskId]
    }
    api.getListHelpdeskById(helpdeskId, (data) => {
        loading.value = false;
        helpdesk.value = data;
    });
};
const selectHelpdesk = (row) => {
    router.push({
        name: "helpdesk-list",
        params: {
            id: row.id,
            category: "helpdesk",
        },
        query: {
            return_url :  encodeURI(window.location.href),
        },
    });
};

onMounted(() => {
    fetchHelpdeskList();
});
</script>
