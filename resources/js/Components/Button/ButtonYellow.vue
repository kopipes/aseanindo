<template>
    <button
        :type="$attrs.type || 'button'"
        class=" bg-yellow w-full flex items-center justify-center gap-1 text-white font-krub-medium text-[12px] py-3 rounded-full border border-yellow disabled:cursor-not-allowed disabled:bg-[#C2C2C2] disabled:border-[#C2C2C2]"
    >
        <IconLoading v-if="loading!==undefined && loading"/>
        <slot />
    </button>
</template>
<script setup>
defineProps(['loading'])
import IconLoading from "../Icon/IconLoading.vue"
</script>
