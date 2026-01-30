<template>
    <div
        class="bg-white h-full w-[100vw] sm:min-w-callnchat sm:max-w-callnchat shadow-md flex flex-col relative"
        x-data="{loading:false,slide: false,alert:false,endConfirmation:false }"
    >
        <Loading class="loading empty"/>
        <Alert />
        <SlideOver>
            <slot name="slide" />
            <div class="slide-over w-full"></div>
        </SlideOver>
        <slot name="header" />
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-[#F8F9FA] flex-content-page-wrapper" v-bind:class="class">
            <slot />
        </div>
        <slot name="bottom" />
        <Footer />
    </div>
</template>
<script setup>
import Footer from "../Components/Footer.vue";
import Loading from "../Components/Popup/Loading.vue";
import SlideOver from "../Components/Popup/SlideOver.vue";
import Alert from "../Components/Popup/Alert.vue";
defineProps(['class'])
</script>
