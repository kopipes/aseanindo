<template>
    <div>
        <p class="text-[13px] font-krub-semibold">
          {{ label }}
        </p>
        <section class="bg-white rounded-md shadow-sm px-3 py-2 mt-[4px]">
            <slot/>
        </section>
    </div>
</template>
<script setup lang="ts">
defineProps(['label'])
</script>