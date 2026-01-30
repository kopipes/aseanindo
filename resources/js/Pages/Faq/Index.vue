<template>
    <Main>
        <template v-slot:menu>
            <ProductContactMenu active="faq" />
        </template>
        <div class="h-full">
            <List
                :items="alFaqs"
                :topFaq="topFaq"
                :loading="loading"
                @select="setFaq"
                v-if="!selected"
            />
            <Detail :faq="selected" @close="resetFaq" v-if="selected" />
        </div>
    </Main>
</template>
<script setup>
import Main from "../../Layout/Main.vue";
import ProductContactMenu from "../../Components/Menu/ProductContactMenu.vue";
import List from "./List.vue";
import Detail from "./Detail.vue";
import { ref, onBeforeMount,onMounted } from "vue";
import { api } from "../../Service/api-service.js";
import { useRoute,useRouter } from "vue-router";
import { useContext } from "../../Hooks/useContext";
import { usePage } from "../../Hooks/usePage";

const page = usePage()
const context = useContext();
const alFaqs = ref([]);
const topFaq = ref([]);
const selected = ref(null);
const loading = ref(true);
const route = useRoute();
const router = useRouter()

const fetchFaqList = () => {
    if(page.has_faq){
        api.getListFaq((result) => {
            alFaqs.value = result.list;
            topFaq.value = result.top;
            loading.value = false;
            context.loading.hide();
            hasQueryParamId();
        });
    }else{
        router.push({
            name : "contact-list"
        })
    }
};

const setFaq = (data) => {
    if (data.id) {
        selected.value = data;
        context.route.appendURLParam({
            id: data.id,
        });
    }
};

const hasQueryParamId = () => {
    const id = route.query.id;
    if (id) {
        alFaqs.value.forEach((row)=>{
            if(row.id==id){
                selected.value = row;
                return;
            }
            row.sub.forEach((sub)=>{
                if(sub.id==id){
                    selected.value = sub
                    return;
                }
                sub.child.forEach((child)=>{
                    if(child.id==id){
                        selected.value = child
                        return;
                    }
                })
            })
        })
        // selected.value = alFaqs.value.find((row) => row.id == id);
    }
};
const resetFaq = () => {
    selected.value = null;
    context.route.removeURLParameter(["id"]);
};

onBeforeMount(() => {
    fetchFaqList();
});

onMounted(()=>{
    console.log('cpkl')
})
</script>
