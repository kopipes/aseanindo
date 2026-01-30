<template>
    <ul class="w-full max-h-[100%]">
        <li
            v-for="(message, index) in conversation"
            class="flex gap-1 mb-3 w-full"
            v-bind:class="{
                'justify-start flex-row-reverse': message.sender === 'customer',
            }"
        >
            <div>
                <img
                    :src="message.sender == 'bot' ? company.logo : guestProfile"
                    width="20"
                    height="20"
                    class="object-cover rounded-full border"
                />
            </div>
            <Message
                :sender="
                    message.sender == 'bot'
                        ? company.profile.brand_name
                        : 'Guest'
                "
                :message="message"
                v-if="
                    ['bot_message', 'message', 'rejection','bot_end'].includes(
                        message.message_type
                    )
                "
            />
            <MessageOption
                :sender="message.sender ? company.profile.brand_name : 'Guest'"
                :message="message"
                :disable="index + 1 < conversation.length"
                @sendMessage="
                    (option) =>
                        $emit('sendMessage', {
                            message: option,
                        })
                "
                v-if="message.message_type === 'bot_message_option'"
            />
            <MessageLink
                :sender="message.sender ? company.profile.brand_name : 'Guest'"
                :message="message"
                :disable="index + 1 < conversation.length"
                v-if="message.message_type === 'message_link'"
            />
            <MessageDataRequest
                :sender="message.sender ? company.profile.brand_name : 'Guest'"
                :message="message"
                :disable="index + 1 < conversation.length"
                @fetchProduct="(callback) => $emit('fetchProduct', callback)"
                @setTitleListProduct="(title)=>$emit('setTitleListProduct',title)"
                @postNextMessage="()=>$emit('postNextMessage')"
                v-if="message.message_type === 'bot_message_data_request'"
            />
            <DetailProduct
                :message="message"
                v-if="message.message_type === 'bot_product'"
            />
            <MessageForm
                :message="message"
                :phones="phones"
                :disable="index + 1 < conversation.length"
                @sendMessage="
                    (value, callback,error) =>
                        $emit('sendBotResponse', {
                            message: value,
                            type: 'form',
                            callback,
                            error
                        })
                "
                @showSlideSubject="(options)=>$emit('showSlideSubject',options)"
                v-if="message.message_type === 'bot_form'"
            />
            <Verification
                :message="message"
                :disable="index + 1 < conversation.length"
                @resendBookingOtp="
                    (callback) => $emit('resendBookingOtp', callback)
                "
                @validateBookingOtp="(otp,success,error)=>$emit('validateBookingOtp',{otp,success,error})"
                v-if="message.message_type === 'bot_verification_email'"
            />

            <VerificationValid
                v-if="message.message_type==='bot_booking_success'"
            />

            <Thinking v-if="message.message_type === 'thinking'" />
        </li>
        <li class="flex gap-1 mb-3 w-full" v-if="thinking">
            <div>
                <img
                    :src="company.logo"
                    width="20"
                    height="20"
                    class="object-cover rounded-full"
                />
            </div>
            <Thinking />
        </li>
        <!-- <pre class="text-[8px]">{{ conversation }}</pre> -->
    </ul>
</template>
<script setup>
import Thinking from "./ChatMessage/Thinking.vue";
import Message from "./ChatMessage/Message.vue";
import MessageOption from "./ChatMessage/MessageOption.vue";
import MessageLink from "./ChatMessage/MessageLink.vue";
import MessageDataRequest from "./ChatMessage/MessageDataRequest.vue";
import DetailProduct from "./ChatMessage/DetailProduct.vue";
import MessageForm from "./ChatMessage/MessageForm.vue";
import Verification from "./ChatMessage/Verification.vue";
import VerificationValid from "./ChatMessage/VerificationValid.vue";
defineProps(["company", "conversation", "thinking", "phones"]);

const guestProfile =
    "https://yelow-app-storage.s3.ap-southeast-1.amazonaws.com/cnako525c6GTGkUq1nefIJ38mXinpV5JovDMuuws.png";
</script>
