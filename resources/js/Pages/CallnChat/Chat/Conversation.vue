<template>
    <ul class="w-full max-h-[100%]">
        <li
            v-for="chat in conversation"
            class="flex gap-1 mb-3 w-full"
            v-bind:class="{
                'justify-start flex-row-reverse': chat.user_id === user_id,
            }"
        >
            <div v-if="chat.type === 'broadcast'" class="w-full px-6">
                <div class="bg-[#dddddd8f] text-[11px] px-3 py-[2px] flex justify-center items-center rounded-md text-center">
                    {{ chat.content }}
                </div>
            </div>

            <div v-if="chat.type === 'information'" class="w-full px-6">
                <div class="bg-[#dddddd8f] text-[11px] px-3 py-[2px] flex justify-center items-center rounded-md text-center">
                    {{ chat.content }}
                </div>
            </div>

            <div v-if="!['information','broadcast'].includes(chat.type)">
                <img
                    :src="chat.profile"
                    width="20"
                    height="20"
                    class="object-cover rounded-full"
                />
            </div>
            <div 
            v-if="!['information','broadcast'].includes(chat.type)"
                class="px-2 py-1 pb-2 rounded-lg max-w-[70%] border "
                v-bind:class="chat.user_id === user_id ? 'bg-[#6B4EFF] text-white' : 'bg-[#F2F4F5]'"
            >
                <div
                    class="flex justify-between text-[10px] font-krub-regular mb-1 gap-3"
                >
                    <span>
                        {{ chat.name }}
                    </span>
                    <span>
                        {{ chat.time }}
                    </span>
                </div>
                <p
                    class="font-krub-medium text-[11px] chat-message-content"
                    v-if="chat.type === 'message'"
                >
                    {{ chat.content }}
                </p>
                <div v-if="chat.type === 'location'">
                    <div class="h-[120px] rounded-md overflow-hidden mb-2">
                        <iframe
                            :src="`https://maps.google.com/maps?q=${chat.content.lat},${chat.content.lng}&hl=es;z=10&output=embed`"
                            class="w-full h-[120px]"
                            frameborder="0"
                        ></iframe>
                    </div>
                    <span class="font-krub-medium text-[12px] block chat-message-content">
                        {{ chat.content.address }}
                    </span>
                </div>
                <a
                    :href="chat.content.url"
                    target="_blank"
                    class="flex gap-2 items-center"
                    v-if="chat.type === 'file'"
                >
                    <div>
                        <div
                            class="bg-[#ddd] h-[40px] w-[40px] rounded-lg flex items-center justify-center"
                        >
                            <i
                                class="isax-b icon-document-text text-[30px] text-[#7c7c7c]"
                            ></i>
                        </div>
                    </div>
                    <div class="flex flex-col overflow-hidden chat-message-content">
                        <p
                            class="font-krub-medium text-[11px] whitespace-normal overflow-hidden line-clamp-1 truncate"
                        >
                            {{ chat.content.name }}
                        </p>
                        <span class="font-krub-regular text-[9px]">
                            {{ chat.content.size }}
                        </span>
                    </div>
                </a>
            </div>
        </li>
        <div class="h-[8px]"></div>
    </ul>
</template>
<script setup lang="ts">
defineProps(["conversation","user_id"]);
</script>
