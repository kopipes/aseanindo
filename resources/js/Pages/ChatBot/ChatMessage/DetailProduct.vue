<template>
    <div
        class="rounded-lg max-w-[70%] border bg-white shadow-sm w-full overflow-hidden"
    >
        <img
            :src="message.message.image"
            alt=""
            class="w-full h-[300px] object-cover"
        />
        <div class="px-2 py-1">
            <b class="text-[13px] font-krub-semibold" v-html="message.message.name">
            </b>
            <p class="font-krub-medium text-[12px] pre-wrap-content">
                {{message.message.description}}
            </p>
        </div>
    </div>
</template>
<script setup>
defineProps(['message'])
</script>