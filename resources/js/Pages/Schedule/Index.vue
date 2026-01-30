<template>
    <Empty>
        <template v-slot:header>
            <header
                class="bg-[#D7D9F1] flex flex-col items-center justify-center py-3 font-krub-bold text-[14px]"
            >
                <div class="flex gap-2 items-center">
                    <img :src="company.logo" :alt="company.profile.brand_name" class="w-[25px] h-[25px] rounded-full">
                    <span>
                        {{ company.profile.brand_name }}
                    </span>
                </div>
                {{ route.meta.title }}
            </header>
        </template>
        <form class="px-3 py-2 bg-[#F7F7F7] h-full" @submit.prevent="submit">
            <ChooseSelect
                label="Choose Product"
                @fetchItem="fetchProduct"
                :value="product.name"
                :disable="action.complete_booking"
                v-if="PRODUCT_TYPE == 'general'"
            />
            <section v-else class="flex flex-col gap-3">
                <ChooseSelect
                    label="Choose Location"
                    @fetchItem="fetchLocation"
                    :value="scheduleForm.location"
                    :disable="action.complete_booking"
                />
                <div
                    v-bind:class="{
                        'text-[#DDDDDD]': scheduleForm.location == '',
                    }"
                >
                    <ChooseSelect
                        :label="
                            PRODUCT_TYPE == 'schedule_other'
                                ? 'Product Name'
                                : 'Professional Type'
                        "
                        @fetchItem="fetchProfessional"
                        :value="
                            scheduleForm.professional_type ||
                            ( PRODUCT_TYPE == 'schedule_other'
                                ? 'Choose Product Name'
                                : 'Choose Professional Type')
                        "
                        :disable="
                            action.complete_booking ||
                            scheduleForm.location == ''
                        "
                    />
                </div>

                <div
                    v-bind:class="{
                        'text-[#DDDDDD]':
                            scheduleForm.location == '' ||
                            scheduleForm.professional_type == '',
                    }"
                >
                    <ChooseSelect
                        :label="
                            PRODUCT_TYPE == 'schedule_other'
                                ? 'PIC Name'
                                : 'Name'
                        "
                        @fetchItem="fetchPicName"
                        :value="
                            scheduleForm.pic_name ||
                            (PRODUCT_TYPE == 'schedule_other'
                                ? 'Choose PIC Name'
                                : 'Choose Name')
                        "
                        :disable="
                            action.complete_booking ||
                            scheduleForm.location == '' ||
                            scheduleForm.professional_type == ''
                        "
                    />
                </div>
                <div
                    v-bind:class="{
                        'text-[#DDDDDD]':
                            scheduleForm.location == '' ||
                            scheduleForm.professional_type == '' ||
                            scheduleForm.pic_name == '',
                    }"
                >
                    <ChooseSelect
                        label="Choose Date"
                        @fetchItem="fetchScheduleDate"
                        :value="scheduleForm.date"
                        :disable="
                            action.complete_booking ||
                            scheduleForm.location == '' ||
                            scheduleForm.professional_type == '' ||
                            scheduleForm.pic_name == ''
                        "
                    />
                </div>
                <ChooseTime
                    :label="
                        PRODUCT_TYPE == 'schedule_other'
                            ? 'Available Time'
                            : 'Choose Time'
                    "
                    :disabled="action.complete_booking"
                    :times="scheduleForm.times"
                    @select="chooseTime"
                    v-if="
                        scheduleForm.location != '' &&
                        scheduleForm.professional_type != '' &&
                        scheduleForm.pic_name != '' &&
                        scheduleForm.date != '' && formLoaded
                    "
                />
                <Input
                    placeholder="Enter Quantity"
                    type="number"
                    label="Quantity"
                    :disabled="action.complete_booking"
                    :required="true"
                    :help="quantity.help"
                    :max="quantity.max"
                    v-model="form.quantity"
                    v-if="schedule.state.forms.length && PRODUCT_TYPE=='schedule_other'"
                />
            </section>
            <Conversation
                :phoneCodes="phoneCodes"
                :forms="schedule.state.forms"
                :data="form"
                :disabled="action.complete_booking"
                @showPhoneCode="showPhoneCode"
                @setPopupSelect="setPopupSelect"
                v-if="formLoaded"
            />
            <VerificationOTP
                :data="action"
                :disable="false"
                @resendBookingOtp="resendBookingOtp"
                @validateBookingOtp="validateBookingOtp"
                v-if="action.show_email_otp"
            />
            <!-- <pre class="text-[8px]">{{ form }}</pre> -->
            <button
                type="submit"
                :disabled="
                    !form.product_id ||
                    !form.name ||
                    !form.email ||
                    !form.phone_number ||
                    !form.subject_id ||
                    ! isValidEmail() || 
                    action.loading ||
                    isAllFormNotFilled() ||
                    !isQuantityValid()
                "
                v-if="!action.show_email_otp && !action.complete_booking"
                class="bg-yellow w-full text-white text-[13px] font-krub-medium px-3 py-2 rounded-md mt-3 mb-5 disabled:bg-[#ddd] disabled:cursor-not-allowed flex gap-4 items-center justify-center"
            >
                Submit
                <IconLoading v-if="action.loading" />
            </button>
            <div
                class="pre-wrap-content bg-white shadow-sm text-center rounded-md text-[13px] font-krub-medium py-3 px-3 mt-2"
                v-if="action.complete_booking"
            >
                {{ schedule.state.closing_statement || "Terimakasih" }}
            </div>
            <div
                v-if="action.error"
                class="bg-[#ffa40042] shadow-sm text-center text-red italic rounded-md text-[13px] font-krub-medium py-3 px-3 mt-2"
            >
                {{ action.error }}
            </div>
            <Complete v-if="action.complete_booking"/>
            <br>
        </form>

        <button
            type="button"
            class="hidden"
            id="toggle-slide-popup"
            x-on:click="slide=!slide"
        ></button>
        <template v-slot:slide>
            <PopupSelect
                :name="popupSelect.type"
                :title="popupSelect.title"
                :items="popupSelect.items"
                :value="popupSelect.value"
                :search="popupSelect.search"
                @choose="popupSelect.choose"
                v-if="popupSelect.type != 'schedule_date'"
            />
            <PopupScheduleDate
                :enables="scheduleForm.enableDates"
                @choose="popupSelect.choose"
                v-else
            />
        </template>
    </Empty>
</template>
<script setup lang="ts">
import Empty from "../../Layout/Empty.vue";
import ChooseSelect from "./Form/ChooseSelect.vue";
import PopupSelect from "./Form/PopupSelect.vue";
import PopupScheduleDate from "./Form/PopupScheduleDate.vue";
import Conversation from "./Conversation.vue";
import IconLoading from "../../Components/Icon/IconLoading.vue";
import VerificationOTP from "./Form/VerificationOTP.vue";
import ChooseTime from "./Form/ChooseTime.vue";
import Complete from "./Form/Complete.vue";
import Input from "./Form/Input.vue"
import { useSchedule } from "../../Hooks/useSchedule";
import { ref, onMounted, reactive } from "vue";
import { useRoute } from "vue-router";
import { usePage } from "../../Hooks/usePage";

const page = usePage()
const route = useRoute();
const schedule = useSchedule();

const company = ref(page.company);
const PRODUCT_TYPE = route.meta.product_type;
const PRODUCT_ID = ref("")

const phoneCodes = ref([]);
const popupSelect = ref({
    items: [],
    title: "",
    type: "",
    value: "",
    search: false,
    choose: () => {},
});
const product = ref({
    id: "",
    name: "",
});

const form = reactive({
    product_id: 0,
    name: "",
    email: "",
    phone_number: "",
    phone_number_code: "62",
    subject_id: "",
});
const scheduleForm = reactive({
    location: "",
    professional_type: "",
    pic_name: "",
    date: "",
    enableDates: [],
    dates: [],
    times : []
});
const action = reactive({
    loading: false,
    email: "",
    complete_booking: false,
    show_email_otp: false,
    otp_verification_id: "",
    otp_verification_valid: false,
    error: null,
});
const quantity = ref({
    help : '',
    max : 0
})
const formLoaded = ref(false)

const isValidEmail = () => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email || '');
}
const resetForm = () => {
    Object.assign(action, {
        loading: false,
        email: "",
        complete_booking: false,
        show_email_otp: false,
        otp_verification_id: "",
        otp_verification_valid: false,
        error: null,
    });
    Object.assign(scheduleForm, {
        location: "",
        professional_type: "",
        pic_name: "",
        date: "",
        enableDates: [],
        dates: [],
        times : []
    });
    resetFormOnly()
};

const resetFormOnly = () =>{
    
    Object.assign(form, {
        product_id: 0,
        name: "",
        email: "",
        phone_number: "",
        phone_number_code: "62",
        subject_id: "",
    });
    schedule.state.forms = []
}

const setPopupSelect = (selectValue) => {
    popupSelect.value = selectValue;
};
const fetchProduct = (callback) => {
    const category = `${PRODUCT_TYPE}?product=${PRODUCT_ID.value}`
    schedule.fetchProductByCategory(category, (data) => {
        popupSelect.value = {
            items: data.map((row) => {
                return {
                    value: row.id,
                    label: row.name,
                };
            }),
            title: "Product List",
            type: "product",
            search: false,
            choose: chooseProduct,
            value: form.product_id,
        };
        document.getElementById("toggle-slide-popup").click();
        callback();
    });
};

const fetchLocation = (callback) => {
    const category = `${PRODUCT_TYPE}?product=${PRODUCT_ID.value}`
    schedule.fetchLocation(category, (data) => {
        popupSelect.value = {
            items: data.map((row) => {
                return {
                    value: row.value,
                    label: row.value,
                };
            }),
            title: "List of available locations",
            type: "location",
            search: false,
            choose: chooseLocation,
            value: "x",
        };
        setTimeout(() => {
            popupSelect.value.value = scheduleForm.location;
        }, 5);
        document.getElementById("toggle-slide-popup").click();
        callback();
    });
};

const fetchProfessional = (callback) => {
    const location = `${scheduleForm.location}&product=${PRODUCT_ID.value}`
    schedule.fetchProfessional(PRODUCT_TYPE, location, (data) => {
        popupSelect.value = {
            items: data.map((row) => {
                return {
                    value: row.value,
                    label: row.value,
                };
            }),
            title: PRODUCT_TYPE == 'schedule_other'
                                ? 'Product Name'
                                : 'Professional Type',
            type: "professional",
            search: false,
            choose: chooseProfessional,
            value: "x",
        };
        setTimeout(() => {
            popupSelect.value.value = scheduleForm.professional_type;
        }, 5);
        document.getElementById("toggle-slide-popup").click();
        callback();
    });
};

const fetchPicName = (callback) => {
    const professional = `${scheduleForm.professional_type}&product=${PRODUCT_ID.value}`
    schedule.fetchPicName(
        PRODUCT_TYPE,
        scheduleForm.location,
        professional,
        (data) => {
            popupSelect.value = {
                items: data.map((row) => {
                    return {
                        value: row.value,
                        label: row.value,
                    };
                }),
                title:
                    PRODUCT_TYPE == "schedule_other"
                        ? "Choose PIC Name"
                        : "Choose Name",
                type: "pic_name",
                search: false,
                choose: choosePicName,
                value: "x",
            };
            setTimeout(() => {
                popupSelect.value.value = scheduleForm.pic_name;
            }, 5);
            document.getElementById("toggle-slide-popup").click();
            callback();
        }
    );
};

const fetchScheduleDate = (callback) => {
    const pic_name = `${scheduleForm.pic_name}&product=${PRODUCT_ID.value}`
    schedule.fetchScheduleDate(
        PRODUCT_TYPE,
        scheduleForm.location,
        scheduleForm.professional_type,
        pic_name,
        (data) => {
            const enableDates = [];
            for (const [date, value] of Object.entries(data)) {
                enableDates.push(date);
            }
            scheduleForm.dates = data;
            scheduleForm.enableDates = enableDates;
            popupSelect.value.type = "schedule_date";
            popupSelect.value.choose = chooseScheduleDate;
            document.getElementById("toggle-slide-popup").click();
            callback();
        }
    );
};

const showPhoneCode = (value, choose) => {
    const items = phoneCodes.value.map((row) => {
        return {
            value: row.dial_code,
            label: `(+${row.dial_code}) ${row.name}`,
        };
    });
    popupSelect.value = {
        items: [
            {
                value: "62",
                label: "(+62) Indonesia",
            },
            ...items,
        ],
        title: "Select Phone Code",
        type: "phone_code",
        search: true,
        choose: choose,
        value: value,
    };
    document.getElementById("toggle-slide-popup").click();
};

const fetchPhoneCountry = () => {
    schedule.fetchPhoneCountry((data) => {
        phoneCodes.value = data;
    });
};

const chooseProduct = (properties) => {
    resetForm();
    formLoaded.value = false
    const callback = (productForms) => {
        properties.callback();
        product.value = {
            id: properties.value,
            name: properties.label,
        };
        form.product_id = properties.value;

        productForms.forEach((productForm) => {
            if (productForm.type == "group") {
                productForm.items.forEach((item) => {
                    form[item.slug] = "";
                });
            } else {
                form[productForm.slug] = "";
            }
        });
        setQuantityHelpText()
        formLoaded.value = true
    };
    const date = scheduleForm?.date || ''
    schedule.chooseProduct(properties.value,date, callback);
};

const chooseLocation = (properties) => {
    scheduleForm.location = properties.value;
    scheduleForm.professional_type = ""
    scheduleForm.pic_name = "";
    scheduleForm.date = "";
    form.product_id = ""
    resetFormOnly()
    properties.callback();
};
const chooseProfessional = (properties) => {
    scheduleForm.professional_type = properties.value;
    scheduleForm.pic_name = "";
    scheduleForm.date = "";
    form.product_id = ""
    resetFormOnly()
    properties.callback();
};
const choosePicName = (properties) => {
    scheduleForm.pic_name = properties.value;
    scheduleForm.date = "";
    form.product_id = ""
    resetFormOnly()
    properties.callback();
};
const chooseScheduleDate = (properties) => {
    if(properties.value!=scheduleForm.date){
        formLoaded.value = false
    }
    scheduleForm.date = properties.value;
    properties.callback();

    const selectedSchedule = scheduleForm.dates[properties.value]
    form.product_id = selectedSchedule.id
    const callback = (productForms) => {
        scheduleForm.times = selectedSchedule.times
        form.time = null
        form.date = properties.value
        productForms.forEach((productForm) => {
            if (productForm.type == "group") {
                productForm.items.forEach((item) => {
                    form[item.slug] = "";
                });
            } else {
                form[productForm.slug] = "";
            }
        });
        setQuantityHelpText()
        formLoaded.value = true
    };
    schedule.chooseProduct(form.product_id,properties.value, callback);
};
const chooseTime = (time) => {
    form.time = time
    setQuantityHelpText()
}

const submit = () => {
    action.loading = true;
    action.email = form.email;
    action.error = null;
    if (schedule.state.verification_email) {
        clearCookieSessionOtp();
        requestEmailVerification();
        return;
    }
    sendBookingProduct();
};

const requestEmailVerification = (callback = null) => {
    schedule.requestOtp(
        {
            product_id: form.product_id,
            email: form.email,
        },
        (data) => {
            action.otp_verification_id = data;
            action.otp_verification_valid = false;
            action.show_email_otp = true;
            action.complete_booking = false;
            if (callback) {
                callback();
            }
        }
    );
};

const clearCookieSessionOtp = () => {
    schedule.clearCookie("otp-interval-second-verification-bot-product-email");
};

const sendBookingProduct = () => {
    const formData = {
        forms: form,
        product_id: form.product_id,
        name: form.name,
        email: form.email,
        phone_number: form.phone_number,
        phone_number_code: form.phone_number_code,
        subject_id: form.subject_id,
        product_category: PRODUCT_TYPE,
        quantity : form.quantity
    };
    schedule.bookingTicket(
        formData,
        (success) => {
            action.complete_booking = true;
        },
        (error) => {
            action.loading = false;
            action.error = error.message;
        }
    );
};

const resendBookingOtp = (callback) => {
    clearCookieSessionOtp();
    requestEmailVerification(callback);
};
const validateBookingOtp = (otp, success, error) => {
    schedule.validateOtp(
        {
            product_id: form.product_id,
            email: form.email,
            otp: otp,
            verification_id: action.otp_verification_id,
        },
        () => {
            action.otp_verification_valid = true;
            action.show_email_otp = false;
            action.loading = true;
            success();
            sendBookingProduct();
        },
        error
    );
};

const isAllFormNotFilled = () => {
    const isValid = Object.values(form).every((value) => {
        return value !== "" && value !== null && value !== undefined;
    });
    return !isValid;
};

const isQuantityValid = () => {
    if(PRODUCT_TYPE=='schedule_other'){
        return parseInt(form.quantity) <=quantity.value.max
    }
    return true;
}
const putQuantity = () => {
    if(PRODUCT_TYPE=='schedule_other'){
        form.quantity = 0
    }
}
const setQuantityHelpText = () => {
    if(form.time){
        const time = form.time
        var booked = 0
        schedule.state.bookeds.forEach((row)=>{
            if(row.counseling_time.start == time.start && row.counseling_time.end==time.end){
                booked += row.number
            }
        })
        quantity.value = {
            help : `Quantity ${booked} of ${time.max} used`,
            max : time.max - booked
        }
    }else{
        quantity.value = {
            help : '',
            max : 0
        }
    }
}
const getProductId = () => {
    var productJson = route.params.product_json
    if(productJson){
        productJson = productJson.replace(/-/g, '+').replace(/_/g, '/');
        productJson =  atob(productJson);
        PRODUCT_ID.value = productJson
    }
}
onMounted(() => {
    getProductId()
    fetchPhoneCountry();
    putQuantity()
    schedule.startSession();
});
</script>
