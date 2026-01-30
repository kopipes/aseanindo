<template>
    <ul class="flex justify-between px-4 gap-2 mb-1">
        <li v-for="menu in menus" class="flex-1 text-center">
            <router-link
                :to="{ name: menu.link }"
                class="text-[14px] font-krub-medium w-full block py-[5px] border rounded-full hover:text-white hover:bg-yellow hover:border-yellow"
                v-bind:class="{
                    'border-yellow bg-yellow text-white': menu.id === active,
                    'text-[#313131]': menu.id !== active,
                }"
            >
                {{ menu.name }}
            </router-link>
        </li>
    </ul>
</template>
<script setup>
import { usePage } from "../../Hooks/usePage";
defineProps(["active"]);

const page = usePage();
const menus = [
    {
        id: "contact",
        name: "Contact Us",
        link: "contact-list",
    },
    {
        id: "product",
        name: "Our Product",
        link: "product-list",
    },
];
if (page.has_faq) {
    menus.push({
        id: "faq",
        name: "FAQ",
        link: "faq-list",
    });
}
</script>
