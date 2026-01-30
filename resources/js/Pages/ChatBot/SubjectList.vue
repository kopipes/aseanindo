<template>
    <div class="w-full mb-[-20px]">
        <p class="text-[14px] mb-2 font-krub-semibold">Choose Subject</p>
        <ul
            x-data="{ 
                activeAccordion: '', 
                setActiveAccordion(id) { 
                        this.activeAccordion = (this.activeAccordion == id) ? '' : id 
                } 
            }"
            class="flex flex-col gap-2 text-[13px] max-h-[60vh] overflow-auto mx-[-20px] px-[20px]"
        >
            <li v-for="subject in items" x-data="{ id: $id('accordion') }">
                <div
                    class="w-full flex justify-between cursor-pointer items-center"
                    x-on:click="setActiveAccordion(id)"
                >
                    <label
                        :for="`subject_${subject.id}`"
                        class="cursor-pointer flex-1 max-w-[90%] pre-wrap-content"
                    >
                        {{ subject.name }}
                    </label>
                    <input
                        type="radio"
                        name="subject_id"
                        class="radio-custom"
                        :id="`subject_${subject.id}`"
                        :value="subject"
                        v-model="selectedSubject"
                        v-if="!subject.subs.length"
                    />
                    <button
                        type="button"
                        class="duration-200 ease-out"
                        x-bind:class="{'rotate-180': activeAccordion == id}"
                        v-else
                    >
                        <i class="isax icon-arrow-down-1"></i>
                    </button>
                </div>
                <ul
                    class="flex flex-col gap-2 text-[13px] max-h-[60vh] overflow-auto mx-[-20px] px-[20px] ps-11 mt-2"
                    v-if="subject.subs.length"
                    x-show="activeAccordion==id"
                    x-data="{ 
                        activeSubAccordion: '', 
                        setActiveSubAccordion(id) { 
                            this.activeSubAccordion = (this.activeSubAccordion == id) ? '' : id 
                        } 
                    }"
                >
                    <li v-for="sub in subject.subs" x-data="{ id: $id('accordion') }">
                        <div
                            class="w-full flex justify-between cursor-pointer items-center"
                            x-on:click="setActiveSubAccordion(id)"
                        >
                            <label
                                :for="`subject_${sub.id}`"
                                class="cursor-pointer flex-1 max-w-[90%] pre-wrap-content"
                            >
                                {{ sub.name }}
                            </label>
                            <input
                                type="radio"
                                name="subject_id"
                                class="radio-custom"
                                :id="`subject_${sub.id}`"
                                :value="sub"
                                v-model="selectedSubject"
                                v-if="!sub.child.length"
                            />
                            <button
                                type="button"
                                class="duration-200 ease-out"
                                x-bind:class="{'rotate-180': activeSubAccordion == id}"
                                v-else
                            >
                                <i class="isax icon-arrow-down-1"></i>
                            </button>
                        </div>
                        <ul
                            class="flex flex-col gap-2 text-[13px] max-h-[60vh] overflow-auto mx-[-20px] px-[20px] ps-11 mt-2"
                            v-if="sub.child.length"
                            x-show="activeSubAccordion==id"
                        >
                            <li v-for="child in sub.child">
                                <div
                                    class="w-full flex justify-between cursor-pointer items-center"
                                >
                                    <label
                                        :for="`subject_${child.id}`"
                                        class="cursor-pointer flex-1 max-w-[90%] pre-wrap-content"
                                    >
                                        {{ child.name }}
                                    </label>
                                    <input
                                        type="radio"
                                        name="subject_id"
                                        class="radio-custom"
                                        :id="`subject_${child.id}`"
                                        :value="child"
                                        v-model="selectedSubject"
                                    />
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        <button
            type="button"
            class="flex gap-4 items-center justify-center bg-yellow text-white text-[12px] font-krub-medium w-full py-2 rounded-md mt-2 mb-[-5px] disabled:bg-[#D9D9D9]"
            :disabled="!selectedSubject || loading"
            @click="chooseSubject"
        >
            Submit
            <IconLoading v-if="loading" />
        </button>
    </div>
</template>
<script setup>
import IconLoading from "../../Components/Icon/IconLoading.vue";
import { ref } from "vue";
const emit = defineEmits(["choose"]);
defineProps(["items"]);
const selectedSubject = ref("");
const loading = ref(false);

const chooseSubject = () => {
    loading.value = true;
    const subject = selectedSubject.value;
    emit("choose", {
        id: subject.id,
        message: subject.name,
        callback: () => {
            loading.value = false;
            document.getElementById("toggle-slide-popup").click();
        },
    });
};
</script>
