<template>
    <div
        class="w-full mb-[-20px] product-schedule-date flex items-center flex-col justify-center"
    >
        <p class="text-[14px] mb-2 font-krub-semibold">Choose Schedule Date</p>
        <div class="schedule-date"></div>
    </div>
</template>
<script setup lang="ts">
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import { ref, onMounted, watch } from "vue";

const props = defineProps(["enables"]);
const emit = defineEmits(["choose"]);

const datePicker: any = ref(null);
onMounted(() => {
    const dateList = props.enables
    var minDate = dateList[0]
    minDate =  Date.parse(minDate)
    if(minDate<=new Date()){
        minDate  = new Date()
    }
    datePicker.value = flatpickr(`.schedule-date`, {
        dateFormat: "Y-m-d",
        time_24hr: true,
        inline: true,
        enable: dateList,
        minDate: minDate,
        maxDate: dateList[dateList.length-1],
        onChange: (selectedDates, dateStr) => {
            emit("choose", {
                value: dateStr,
                label: dateStr,
                callback: () => {
                    document.getElementById("toggle-slide-popup")?.click();
                },
            });
        },
    });
});

watch(
    () => props.enables,
    (value, val) => {
        datePicker.value.set("enable", props.enables);
    }
);
</script>
