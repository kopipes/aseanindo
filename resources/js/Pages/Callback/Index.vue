<template>
    <Empty class="bg-white">
        <template v-slot:header>
            <button
                type="button"
                class="flex items-center gap-2 text-[13px] font-krub-bold px-4 py-3"
                @click="back"
            >
                <i class="isax icon-arrow-left-2 text-[15px]"></i>
                Back to Helpdesk
            </button>
        </template>
        <form
            @submit.prevent="login"
            class="font-krub-bold text-[12px] px-5 py-3"
        >
            <p class="mb-4">Please enter your details</p>
            <Input
                label="Full Name"
                icon="isax-b icon-profile-circle"
                v-model="form.name"
                :value="form.name"
                maxlength="50"
                required
            />
            <PhoneNumber
                label="Phone Number / Whatsapp Number"
                type="number"
                placeholder="Phone Number"
                icon="isax-b icon-call"
                v-model="form.phone"
                @setCode="(code) => (form.phone_code = code)"
                required
            />
            <Input
                label="Email"
                type="email"
                placeholder="Email"
                icon="isax-b icon-sms"
                :error="form.email && !isValidEmail() ?'Email format is not valid' : ''"
                v-model="form.email"
            />
            <Textarea
                label="How can we help?"
                placeholder="Enter text here ..."
                maxlength="500"
                v-model="form.help"
                required
            />
            <!-- <Select
                label="Which product are you interested in?"
                required
                v-model="form.j"
            >
                <option value="">Choose Product</option>
                <option :value="product.id" v-for="product in listProduct">
                    {{ product.name }}
                </option>
            </Select> -->
            <div
                class="flex items-start gap-3 font-krub-regular text-justify mb-6"
            >
                <input
                    type="checkbox"
                    v-model="form.agreement"
                    value="1"
                    id="agreement"
                    class="mt-[2px]"
                />
                <label for="agreement">
                    By signing up you understand and agree to Kontakami
                    <a
                        :href="context.page.term_condition"
                        target="_blank"
                        class="text-yellow"
                    >
                        Terms and Conditions
                    </a>
                    and
                    <a
                        :href="context.page.privacy_policy"
                        target="_blank"
                        class="text-yellow"
                    >
                        Privacy Policies
                    </a>
                    and i have agreed to be contacted by your customer service
                </label>
            </div>
            <ButtonYellow
                type="submit"
                :disabled="
                    !form.name ||
                    !form.phone ||
                    !form.email ||
                    !form.help ||
                    // !form.j ||
                    !form.agreement ||
                    form.loading ||
                    !isValidPhone() ||
                    !isValidEmail()
                "
                :loading="form.loading"
            >
                Submit
            </ButtonYellow>
        </form>
    </Empty>
</template>
<script setup>
import Empty from "../../Layout/Empty.vue";
import Input from "../../Components/Input/Input.vue";
import PhoneNumber from "../../Components/Input/PhoneNumber.vue";
import Textarea from "../../Components/Input/Textarea.vue";
import Select from "../../Components/Input/Select.vue";
import ButtonYellow from "../../Components/Button/ButtonYellow.vue";
import { api } from "../../Service/api-service.js";
import { ref, reactive, onBeforeMount } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useContext } from "../../Hooks/useContext";
import { useAuth } from "../../Hooks/useAuth";
import { usePage } from "../../Hooks/usePage";

const router = useRouter();
const route = useRoute();
const context = useContext();
const auth = useAuth(router);
const form = reactive({
    name: "",
    phone: "",
    phone_code: "62",
    email: "",
    help: "",
    product_id: "",
    loading: false,
    agreement: null,
});
const listProduct = ref([]);
const category = ref(route.params.category);
const id = ref(route.query.history || route.params.id);

const back = () => {
    router.back();
};

const fetchProductList = () => {
    // if (auth.user_id) {
    //     router.push({ name: "product-list" });
    // }
    // api.getListProduct((result) => {
    //     listProduct.value = result;
    // });
};
const isValidPhone = () => {
    return form.phone. substring(0, 1)!='0'
}

const isValidEmail = () => {
  return String(form.email)
    .toLowerCase()
    .match(
      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
};
const login = () => {
    const cookieLiveSource = context.cookie.get("live-source");
    if (category.value === "callback" && cookieLiveSource) {
        category.value = cookieLiveSource.category;
    }

    context.loading.toggle();
    form.loading = true;

    api.getListHelpdeskId({
        id: id.value,
        category: category.value,
    }).then((result) => {
        auth.login(
            {
                company_name: usePage().company.name,
                email: form.email,
                phone: form.phone,
                name: form.name,
                phone_code: form.phone_code,
                product_id : form.product_id,
                note : form.help,
                helpdesk_id: result.data,
            },
            () => {
                context.loading.toggle();
                form.loading = false;
                router.push({ name: "product-list" });
            },
            () => {
                context.loading.toggle();
                form.loading = false;
            }
        );
    });
};

onBeforeMount(() => {
    fetchProductList();
});
</script>
