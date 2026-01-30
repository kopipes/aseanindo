<template>
    <div class="mt-3">
        <div
            class="px-2 py-2 pb-2 rounded-lg shadow-sm border border-yellow bg-white text-center w-full"
            v-if="!data.otp_verification_valid"
        >
            <p class="font-krub-semibold text-[13px] mb-1">Verification code</p>
            <p class="font-krub-medium text-[11px] mb-1">
                Please enter the 4 digit verification code that we sent to your
                email address {{ data.email }}
            </p>
            <input
                type="number"
                maxlength="4"
                class="w-full border text-center text-[12px] font-krub-medium py-2 rounded-md outline-none"
                placeholder="0000"
                :readonly="disable || data.complete_booking"
                v-model="otp"
                @keypress="isNumber($event)"
                @copy="disableCopyPaste"
                @paste="disableCopyPaste"
            />
            <p class="text-[11px] font-krub-medium mt-1 text-red" v-if="error">
                {{ error }}
            </p>
            <p
                class="font-krub-medium text-[12px] mt-2"
                v-if="countdown && !disable"
            >
                Try again in {{ countdown }}
            </p>
            <button
                @click="resendOtp"
                class="font-krub-medium text-[12px] mt-2"
                v-if="!countdown && !disable && !data.complete_booking"
            >
                Resend
            </button>
        </div>
        <button
            type="button"
            class="bg-yellow text-white w-full py-2 rounded-md text-[12px] font-krub-medium mt-2 mb-2 disabled:bg-[#D9D9D9] flex gap-4 items-center justify-center"
            :disabled="otp === '' || loading || disable"
            @click="submit"
        >
            Submit
            <IconLoading v-if="loading" />
        </button>
    </div>
</template>
<script setup>
import IconLoading from "../../../Components/Icon/IconLoading.vue";
import { useContext } from "../../../Hooks/useContext";
import { onMounted, ref } from "vue";

const emit = defineEmits(["resendBookingOtp", "validateBookingOtp"]);
const props = defineProps(["data", "disable"]);

const context = useContext();

const otp = ref("");
const intervalId = ref(0);
const countdown = ref("");
const error = ref("");
const loading = ref(false);

const disableCopyPaste = (event) => {
    event.preventDefault();
};
const isNumber = (evt) => {
    if (evt.target.value.length >= 4) {
        evt.preventDefault();
    }
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (
        charCode > 31 &&
        (charCode < 48 || charCode > 57) &&
        [46, 47, 44, 43, 45, 101].includes(charCode)
    ) {
        evt.preventDefault();
    }
    return true;
};

/**
 * Countdown function to resend otp again
 */
const resendOtpInterval = () => {
    countdown.value = "1m59s";
    if (intervalId.value) {
        clearInterval(intervalId.value);
    }
    const intervalDuration =
        parseInt(
            context.cookie.get(
                `otp-interval-second-verification-bot-product-email`
            )
        ) || 120;

    let canResendOtpAt = new Date();
    canResendOtpAt.setSeconds(canResendOtpAt.getSeconds() + intervalDuration);

    intervalId.value = setInterval(() => {
        let currentTime = new Date();
        const timeLeft = canResendOtpAt.getTime() - currentTime.getTime();
        let seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
        let minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        context.cookie.set(
            `otp-interval-second-verification-bot-product-email`,
            (minutes * 60 + seconds).toString()
        );
        if (timeLeft > 0) {
            countdown.value = `${minutes}m${seconds}s`;
        } else {
            context.cookie.set(
                `otp-interval-second-verification-bot-product-email`,
                "0"
            );
            countdown.value = "";
            clearInterval(intervalId.value);
        }
    }, 1000);
};

const resendOtp = () => {
    error.value = "";
    otp.value = "";
    context.cookie.del(`otp-interval-second-verification-bot-email`);
    emit("resendBookingOtp", () => {
        resendOtpInterval();
    });
};

const submit = () => {
    loading.value = true;
    error.value = "";
    emit(
        "validateBookingOtp",
        otp.value,
        (success) => {
            if (intervalId.value) {
                clearInterval(intervalId.value);
            }
            loading.value = false;
            context.cookie.del(`otp-interval-second-verification-bot-email`);
        },
        (err) => {
            loading.value = false;
            error.value = err.message;
        }
    );
};

onMounted(() => {
    if (!props.disable && !props.data.complete_booking) {
        console.log("start");
        resendOtpInterval();
    }
});
</script>
