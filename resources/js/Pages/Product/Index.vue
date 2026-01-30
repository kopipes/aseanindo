<template>
    <Main>
        <template v-slot:menu>
            <ProductContactMenu active="product" />
        </template>
        <div>
            <ProductList
                :products="listProduct"
                :loading="loading"
                @setProduct="setProduct"
                v-if="!product"
            />
            <ProductDetail :product="product" @reset="reset" v-else />
        </div>
    </Main>
</template>
<script setup>
import Main from "../../Layout/Main.vue";
import ProductContactMenu from "../../Components/Menu/ProductContactMenu.vue";
import ProductList from "./ProductList.vue";
import ProductDetail from "./ProductDetail.vue";
import { useRoute, useRouter } from "vue-router";
import { ref, onBeforeMount,onMounted } from "vue";
import { api } from "../../Service/api-service.js";
import { useSound } from "../../Hooks/useSound";
import { useContext } from "../../Hooks/useContext";


const context = useContext()
const router = useRouter();
const route = useRoute();
const product = ref(null);
const listProduct = ref([]);
const loading = ref(true);

const setProduct = (data) => {
    product.value = data;
    context.route.appendURLParam({
        id : data.id
    })
};
const reset = () => {
    product.value = null;
    resetIdParam();
};

const fetchProductList = () => {
    api.getListProduct((result) => {
        listProduct.value = result;
        loading.value = false;
        context.loading.hide()
        hasQueryParamId();
    });
};

const hasQueryParamId = () => {
    const id = route.query.id;
    if (id) {
        product.value = listProduct.value.find((row) => row.id == id);
    }
};
const resetIdParam = () => {
    const id = route.query.id;
    if (id) {
        router.replace({ query: null });
        context.route.removeURLParameter(['id'])
    }
};

onBeforeMount(() => {
    fetchProductList();
});

onMounted(()=>{
    useSound().stopRinging()
})
</script>
