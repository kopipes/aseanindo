<template>
    <div>
        <p class="text-[13px] font-krub-semibold">{{label}}</p>
        <ul
            class="bg-white rounded-md shadow-sm mt-[4px] flex flex-wrap gap-2 px-3 py-2"
        >
            <li v-for="time in times" class="flex flex-wrap">
                <button
                    @click="() => chooseTime(time)"
                    :disabled="time.is_full || !time.enable || disabled"
                    type="button"
                    v-bind:class="{
                        'bg-yellow text-white border-yellow': selectedTimeId == time.id,
                        'text-yellow border-yellow': selectedTimeId != time.id &&!time.is_full && time.enable,
                        'text-white bg-red border-red hover:bg-red': time.is_full,
                        'bg-[#ddd] hover:bg-[#ddd] border-[#ddd] hover:text-[#000]': !time.enable && !time.is_full
                    }"
                    class="text-[11px]  border rounded-md font-krub-semibold px-2 py-1 hover:text-white hover:bg-yellow disabled:cursor-not-allowed"
                >
                    <span class="block text-start text-[9px]" v-if="time.is_full">Fully Booked</span>
                    {{ time.start }} - {{ time.end }} 
                </button>
            </li>
            
            <p class="text-[11px]" v-if="!times.length">Loading ...</p>
        </ul>
    </div>
</template>
<script setup lang="ts">
import { ref } from "vue";
defineProps(["times","label","disabled"]);
const emit = defineEmits(["select"]);

const selectedTimeId = ref("");

const chooseTime = (time) => {
    selectedTimeId.value = time.id;
    emit("select", time);
};
</script>
