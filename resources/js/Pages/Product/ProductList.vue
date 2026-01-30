<template>
    <SlideUpAnimation>
        <ul
            class="grid grid-cols-2 gap-3 px-3 py-3 transition-transform transform translate-y-0 duration-300"
        >
            <li v-for="n in 6" v-if="loading">
                <div
                    class="w-full h-[170px] bg-[#dddddd48] rounded-lg"
                ></div>
            </li>
            <li v-for="product in products" v-else>
                <button type="button" @click="$emit('setProduct', product)" class="w-[100%] h-[100%]">
                    <img
                        :src="product.image"
                        :alt="product.name"
                        :title="product.name"
                        class="object-cover rounded-lg shadow-sm bg-[#dddddd48] h-full w-[100%]"
                    />
                </button>
            </li>
        </ul>
    </SlideUpAnimation>
</template>
<script setup>
import SlideUpAnimation from "../../Components/SlideUpAnimation.vue";

defineProps(["loading", "products"]);
</script>
