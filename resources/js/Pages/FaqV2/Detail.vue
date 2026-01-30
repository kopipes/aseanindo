<template>
    <SlideUpAnimation>
        <section v-if="loading">
            <span
                class="bg-slate-200 animate-pulse h-[20px] w-[200px] rounded-md block mb-2"
            ></span>
            <div class="bg-[#F5F7FC] border rounded-md px-2 py-2">
                <span
                    class="bg-slate-200 animate-pulse h-[15px] w-full rounded-md block mb-2"
                ></span>
                <span
                    class="bg-slate-200 animate-pulse h-[15px] w-[90%] rounded-md block mb-2"
                ></span>
                <span
                    class="bg-slate-200 animate-pulse h-[15px] w-[95%] rounded-md block mb-2"
                ></span>
                <span
                    class="bg-slate-200 animate-pulse h-[15px] w-[85%] rounded-md block mb-2"
                ></span>
            </div>
        </section>
        <section v-if="!loading && faq">
            <button
                type="button"
                class="flex items-center gap-2 text-[14px] font-krub-bold mb-2 text-start"
                @click="back"
            >
                <i class="isax icon-arrow-left-2 text-[17px]"></i>
                {{ faq.name }}
            </button>
            <div class="bg-[#F5F7FC] border rounded-md px-2 py-2">
                <p
                    class="text-[13px] font-krub-regular chat-message-content faq-content bg-[#F5F7FC] px-2 py-2 rounded-md"
                    id="faq_content_description"
                    v-if="faq?.content!=''"
                    v-html="faq?.description"
                ></p>
                <ul
                    class="flex flex-col gap-2 mt-2 mb-2"
                    v-if="subChilds.length"
                >
                    <li v-for="subChild in subChilds">
                        <button
                            type="button"
                            class="flex justify-between items-center text-[13px] bg-white font-krub-semibold py-2 w-full border rounded-md px-3 text-start"
                            @click="$emit('selectFaq', subChild)"
                        >
                            {{ subChild.name }}
                            <i
                                class="isax icon-arrow-right-3 duration-200 ease-out text-dark text-[18px]"
                            ></i>
                        </button>
                    </li>
                </ul>
            </div>
            <br />
            <div
                class="sticky bottom-0 bg-white py-3 flex justify-center"
                v-if="faq.product_id"
            >
                <button
                    class="bg-yellow text-white w-[110px] text-[12px] rounded-md py-2 flex gap-2 items-center justify-center"
                    @click="call"
                >
                    <IconContactUs />
                    Contact Us
                </button>
            </div>
        </section>
    </SlideUpAnimation>
</template>
<script setup>
import SlideUpAnimation from "../../Components/SlideUpAnimation.vue";
import IconContactUs from "../../Components/Icon/IconContactUs.vue";
import { ref, watch, onMounted } from "vue";
import { api } from "../../Service/api-service.js";
import { useRouter } from "vue-router";

const faq = ref(null);
const loading = ref(false);
const router = useRouter();
const subChilds = ref([]);

const props = defineProps(["search", "id"]);

const fetchDetailFaq = () => {
    loading.value = true;
    api.getDetailFaq(props.id, (result) => {
        faq.value = result;
        subChilds.value = result.subs || [];
        loading.value = false;
    });
};

const highlight = () => {
    const searchText = props.search;
    var inputText = document.getElementById("faq_content_description");
    var text = faq.value.description;
    if (faq.value && searchText) {
        const tempDiv = document.createElement("div");
        tempDiv.innerHTML = text;
        function highlightNodes(node) {
            if (node.nodeType === Node.TEXT_NODE) {
                const regex = new RegExp(`(${searchText})`, "gi");
                const replacement = `<span class="highlight">$1</span>`;
                const highlightedText = node.textContent.replace(
                    regex,
                    replacement
                );

                const span = document.createElement("span");
                span.innerHTML = highlightedText;

                while (span.firstChild) {
                    node.parentNode.insertBefore(span.firstChild, node);
                }
                node.parentNode.removeChild(node);
            } else if (node.nodeType === Node.ELEMENT_NODE && node.childNodes) {
                node.childNodes.forEach(highlightNodes);
            }
        }

        highlightNodes(tempDiv);
        inputText.innerHTML = tempDiv.innerHTML;
    } else {
        inputText.innerHTML = text;
    }
};

const back = () => {
    const { parent_id } = faq.value;
    if (parent_id) {
        router.push({
            name: "faq-detail",
            params: {
                id: parent_id,
            },
        });
    } else {
        router.push({
            name: "faq-list",
        });
    }
};
const call = () => {
    router.push({
        name: "helpdesk-list",
        params: {
            id: faq.value.product_id,
            category: "product",
        },
    });
};

onMounted(() => {
    fetchDetailFaq();
});

watch(
    () => props.search,
    (val, value) => {
        highlight();
    }
);
</script>
