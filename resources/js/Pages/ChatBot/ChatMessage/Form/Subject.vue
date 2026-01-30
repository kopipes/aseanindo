<template>
    <div class="w-full">
        <div class="px-2 py-1 pb-2 rounded-lg border bg-[#F2F4F5] w-full ">
            <p class="font-krub-medium text-[11px] chat-message-content">
                Please Select Subject
            </p>
            <button
                type="button"
                class="flex border-t-2 border-white mt-1 text-[11px] font-krub-semibold items-center justify-center gap-2 text-[#0168A5] w-full pt-2 disabled:text-[#D9D9D9]"
                :disabled="disable"
                @click="$emit('showSlideSubject')"
            >
                <i class="isax icon-textalign-justifycenter"></i>
                Choose Subject
            </button>
        </div>
    </div>
</template>
<script setup>
const props = defineProps(["placeholder", "valid", "disable","last"]);

</script>
