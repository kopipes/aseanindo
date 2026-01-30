<template>
    <div class="px-2 py-1 pb-2 rounded-lg max-w-[70%] border bg-[#F2F4F5]">
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
        <p class="font-krub-medium text-[11px] chat-message-content">
            <span v-html="message.message.message || message.message">
            </span>
        </p>
    </div>
</template>
<script setup>
defineProps(["sender", "message"]);
</script>
