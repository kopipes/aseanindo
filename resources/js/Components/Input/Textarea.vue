<template>
    <div>
        <label
            for=""
            v-if="label"
            class="block font-krub-semibold text-[12px] mb-1"
        >
            {{ label }}
        </label>
        <div
            class="relative mb-2"
            x-data="{input: $el.getAttribute('data-value')}"
          :data-value="$attrs.value || ''"
        >
            <textarea
                x-model="input"
                v-bind="$attrs"
                rows="4"
                @input="$emit('update:modelValue', $event.target.value)"
                class="bg-[#F2F2F4] w-full rounded-[3px] border-2 resize-none outline-none border-[#F2F2F4] focus:bg-white focus:border-yellow font-krub-medium px-4 py-[10px]"
                :placeholder="placeholder || label"
            />
            <div class="mb-4 right-0 top-0" v-if="$attrs.maxlength">
                <p class="text-[11px] text-[#ddd] float-right font-krub-regular">
                    <span x-text="input.length"></span> / {{ $attrs.maxlength }}
                </p>
            </div>
        </div>
    </div>
</template>
<script setup>
defineProps(["label", "placeholder"]);
</script>
