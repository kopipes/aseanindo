<template>
     <div class="w-[80%] mb-[-20px]">
          <p class="text-[#7A7E80] text-[14px] text-center mb-4">
               Are you sure you want to end this conversation?
          </p>
          <ButtonYellow
                type="button"
                class="mb-2 font-krub-semibold text-[12px] py-[10px]"
                @click="$emit('confirm')"
                x-on:click="slide=false"
            >
               Yes
            </ButtonYellow>
            <button
                type="button"
                class="font-krub-semibold text-[12px] w-full text-[#B4B6B8] mt-1 py-2"
                @click="$emit('cancel')"
                x-on:click="slide=false"
            >
                No
            </button>
     </div>
</template>
<script setup>
import ButtonYellow from "../../../Components/Button/ButtonYellow.vue";
</script>