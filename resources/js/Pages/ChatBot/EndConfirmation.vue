<template>
    <div>
        <div
            class="bg-[#00000085] h-full absolute w-full z-20 flex items-center justify-center top-0"
            x-show="endConfirmation"
        >
            <div class=" h-full w-full absolute" x-on:click="endConfirmation=false">
            </div>
            <div
                class="bg-white min-w-[65%] max-w-[80%] opacity-100 text-dark flex items-center text-center gap-2 rounded-xl text-[13px] px-6 py-7 flex-col absolute"
            >
                <svg
                    width="40"
                    height="40"
                    viewBox="0 0 40 40"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M20 1.25C9.65 1.25 1.25 9.65 1.25 20C1.25 30.35 9.65 38.75 20 38.75C30.35 38.75 38.75 30.35 38.75 20C38.75 9.65 30.35 1.25 20 1.25ZM20 31.25C18.625 31.25 17.5 30.125 17.5 28.75C17.5 27.375 18.625 26.25 20 26.25C21.375 26.25 22.5 27.375 22.5 28.75C22.5 30.125 21.375 31.25 20 31.25ZM22.8625 11.8625L22.025 21.8875C21.9375 22.9375 21.0625 23.75 20 23.75C18.9375 23.75 18.0625 22.9375 17.975 21.8875L17.1375 11.8625C17 10.1875 18.3125 8.75 20 8.75C21.6 8.75 22.875 10.05 22.875 11.625C22.875 11.7 22.875 11.7875 22.8625 11.8625Z"
                        fill="#FF5247"
                    />
                </svg>
                <p>Are you sure you want to leave?</p>
                <div class="flex gap-3">
                    <button
                        type="button"
                        class="border text-[13px] w-[90px] py-1 rounded-md"
                        x-on:click="endConfirmation=false"
                    >
                        No
                    </button>
                    <button
                        type="button"
                        @click="$emit('confirm')"
                        class="bg-yellow border border-yellow text-[13px] w-[90px] py-1 rounded-md text-white"
                    >
                        Yes
                    </button>
                </div>
            </div>
        </div>
        <button
            type="button"
            x-on:click="endConfirmation=true"
            id="show-endConfirmation"
            class="hidden"
        ></button>
    </div>
</template>
