<template>
    <div
        class="bg-[#00000085] h-full absolute w-full z-10 flex flex-col items-end"
        x-show="slide"
    >
        <div class="h-[100px] flex-1 w-full" x-on:click="slide=false"></div>
        <div
            class="bg-white w-full opacity-100 text-dark flex flex-col items-center gap-2 rounded-t-2xl text-[13px] px-6 py-3 pb-8 max-h-[95vh]"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            x-show="slide"
        >
            <span
                class="bg-[#7A7E80] w-[50px] h-[5px] rounded-full block mb-3"
            ></span>
            <slot />
        </div>
    </div>
</template>
