<template>
    <div class="max-w-[70%] w-full">
        <div class="px-2 py-1 pb-2 rounded-lg border bg-[#F2F4F5] w-full">
            <div
                class="flex justify-between text-[10px] font-krub-regular mb-1 gap-3"
            >
                <span> 
                    {{ sender }}
                </span>
                <span>
                    {{ message.time }}
                </span>
            </div>
            <p class="font-krub-medium text-[11px] chat-message-content" v-html="message.message.message">
            </p>
        </div>
        <ul class="mt-1 mb-1 flex flex-col w-full gap-1 bg-[#F2F4F5] px-2 py-2 border rounded-lg">
            <li v-for="option in message.message.options">
                <button
                    type="button"
                    class="bg-yellow text-white text-[11px] font-krub-medium w-full py-1 rounded-md disabled:bg-[#D9D9D9]"
                    :disabled="disable"
                    @click="$emit('sendMessage',option.title)"
                >
                    {{option.title}}
                </button>
            </li>
        </ul>
    </div>
</template>
<script setup>
defineProps(['sender','message',"disable"])
</script>