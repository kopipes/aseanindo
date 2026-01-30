<template>
    <div>
        <div class="px-6 py-4">
            <button
                type="button"
                class="flex items-center gap-2 text-[15px] font-krub-bold mb-3"
                @click="$emit('back')"
            >
                <i class="isax icon-arrow-left-2 text-[17px]"></i>
                {{ name }}
            </button>
            <div
                class="flex flex-col items-center text-[14px] font-krub-bold gap-3"
            >
                <p>We apologize!</p>
                <EmptyHelpdeskIcon />
                <p class="mt-2">Out help desks are currently not available</p>
            </div>
            <div
                class="bg-[#F2F2F4] text-[13px] font-krub-bold px-3 py-3 mt-3 rounded-md"
            >
                <p class="mb-2">Office Hours</p>
                <ul class="font-krub-regular flex flex-col gap-2 text-[12px]">
                    <li
                        class="flex justify-between items-center"
                        v-for="hour in officeHours"
                    >
                        <span>{{ hour.name }}</span>
                        <span
                            class="bg-[#0D6EFD] text-white text-[10px] py-1 w-[90px] text-center rounded-sm"
                            v-if="hour.value"
                        >
                            {{ hour.value }}
                        </span>
                        <span
                            class="bg-[#DC3545] text-white text-[10px] py-1 w-[90px] text-center rounded-sm"
                            v-else
                        >
                            Closed
                        </span>
                    </li>
                </ul>
            </div>
            <div class="text-center font-krub-bold text-[14px] my-3">
                Would you like us to contact you once we are available during
                office hours?
            </div>
            <ButtonYellow
                @click="requestCallback"
                :loading="loading"
                :disabled="loading"
            >
                Yes
            </ButtonYellow>
        </div>
    </div>
</template>
<script setup>
import ButtonYellow from "../../Components/Button/ButtonYellow.vue";
import EmptyHelpdeskIcon from "../../Components/Icon/EmptyHelpdeskIcon.vue";
import { useAuth } from "../../Hooks/useAuth";
import { useContext } from "../../Hooks/useContext";
import { api } from "../../Service/api-service";
import { useRouter, useRoute } from "vue-router";
import { ref, onBeforeMount } from "vue";

const props = defineProps(["name", "officeHour"]);

const router = useRouter();
const route = useRoute();
const auth = useAuth();
const context = useContext();
const loading = ref(false);
const officeHours = ref([]);
const category = ref(route.params.category);

const requestCallback = () => {
    if (auth.user_id) {
        loading.value = true;
        const cookieLiveSource = context.cookie.get("live-source");
        if (category.value === "callback" && cookieLiveSource) {
            category.value = cookieLiveSource.category;
        }

        api.getListHelpdeskId({
            id: route.params.id,
            category: category.value,
        }).then((result) => {
            api.postRequestCallCallback(result.data, () => {
                router.push({ name: "product-list" });
                loading.value = false;
            });
        });
    } else {
        router.push({
            name: "request-callback",
            params: {
                id: route.params.id,
                category: route.params.category,
            },
        });
    }
};

const isCanRequestCallback = () => {
    const now = new Date();
    var totalRequest = 0;
    var cookieRequest = context.cookie.get("callback-request");
    if (cookieRequest) {
        if (now > Date.parse(cookieRequest.expiry)) {
            context.cookie.del("callback-request");
        } else {
            totalRequest = cookieRequest.value;
        }
    }
    return totalRequest >= 3;
};

const buildOfficeHourList = () => {
    const officeHour = props.officeHour;
    officeHours.value = [
        {
            name: "Monday",
            value: officeHour.monday
                ? `${officeHour.monday?.start || "00:00"} - ${
                      officeHour.monday?.end || "23:59"
                  }`
                : null,
        },
        {
            name: "Tuesday",
            value: officeHour.tuesday
                ? `${officeHour.tuesday?.start || "00:00"} - ${
                      officeHour.tuesday?.end || "23:59"
                  }`
                : null,
        },
        {
            name: "Wednesday",
            value: officeHour.wednesday
                ? `${officeHour.wednesday?.start || "00:00"} - ${
                      officeHour.wednesday?.end || "23:59"
                  }`
                : null,
        },
        {
            name: "Thursday",
            value: officeHour.thursday
                ? `${officeHour.thursday?.start || "00:00"} - ${
                      officeHour.thursday?.end || "23:59"
                  }`
                : null,
        },
        {
            name: "Friday",
            value: officeHour.friday
                ? `${officeHour.friday?.start || "00:00"} - ${
                      officeHour.friday?.end || "23:59"
                  }`
                : null,
        },
        {
            name: "Saturday",
            value: officeHour.saturday
                ? `${officeHour.saturday?.start || "00:00"} - ${
                      officeHour.saturday?.end || "23:59"
                  }`
                : null,
        },
        {
            name: "Sunday",
            value: officeHour.sunday
                ? `${officeHour.sunday?.start || "00:00"} - ${
                      officeHour.sunday?.end || "23:59"
                  }`
                : null,
        },
    ];
};

onBeforeMount(() => {
    buildOfficeHourList();
});
</script>
