<template>
    <Main>
        <template v-slot:menu>
            <ProductContactMenu active="faq" />
        </template>
        <div class="h-full px-3 pb-2">
            <TopSearchFaq @selectFaq="selectFaq" v-model="search" />
            <div v-if="!loading">
                <List
                    @selectFaq="selectFaq"
                    :search="search"
                    v-if="!selectedFaqId"
                />
                <Detail
                    @selectFaq="selectFaq"
                    :search="search"
                    :id="selectedFaqId"
                    v-else
                />
            </div>
        </div>
    </Main>
</template>
<script setup>
import Main from "../../Layout/Main.vue";
import ProductContactMenu from "../../Components/Menu/ProductContactMenu.vue";
import TopSearchFaq from "./TopSearchFaq.vue";
import List from "./List.vue";
import Detail from "./Detail.vue";
import { useRoute, useRouter } from "vue-router";
import { ref, onMounted } from "vue";

const router = useRouter();
const route = useRoute();
const search = ref("");
const selectedFaqId = ref(null);
const loading = ref(true);

const selectFaq = (faq) => {
    router.push({
        name: "faq-detail",
        params: {
            id: faq.id,
        },
    });
};

const listenerDetailFaq = () => {
    const faqId = route.params.id;
    const faqQueryId = route.query.id;
    selectedFaqId.value = faqId || faqQueryId;
    setTimeout(() => {
        loading.value = false;
    });
};

onMounted(() => {
    listenerDetailFaq();
});
</script>
