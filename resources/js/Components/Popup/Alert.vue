<template>
    <div>
        <div
            class="bg-[#00000085] h-full absolute w-full z-20 flex items-center justify-center"
            x-show="alert"
            x-on:click="alert=false"
        >
            <div
                class="bg-white w-fit max-w-[70%] opacity-100 text-dark flex items-center text-center gap-2 rounded-xl text-[12px] px-6 py-7 flex-col"
            >
                <button
                    type="button"
                    x-on:click="alert=true"
                    class="w-full flex justify-end mt-[-9px] mb-[5px]"
                >
                    <svg
                        width="14"
                        height="14"
                        viewBox="0 0 14 14"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M8.28261 6.99996L13.7339 1.54847C14.0887 1.19388 14.0887 0.620544 13.7339 0.265948C13.3793 -0.0886492 12.806 -0.0886492 12.4514 0.265948L6.99992 5.71744L1.5486 0.265948C1.19384 -0.0886492 0.620669 -0.0886492 0.266072 0.265948C-0.0886907 0.620544 -0.0886907 1.19388 0.266072 1.54847L5.71739 6.99996L0.266072 12.4515C-0.0886907 12.806 -0.0886907 13.3794 0.266072 13.734C0.442789 13.9109 0.675145 13.9997 0.907336 13.9997C1.13953 13.9997 1.37172 13.9109 1.5486 13.734L6.99992 8.28249L12.4514 13.734C12.6283 13.9109 12.8605 13.9997 13.0927 13.9997C13.3249 13.9997 13.5571 13.9109 13.7339 13.734C14.0887 13.3794 14.0887 12.806 13.7339 12.4515L8.28261 6.99996Z"
                            fill="black"
                        />
                    </svg>
                </button>
                <div id="alert-content"></div>
            </div>
        </div>
        <button
            type="button"
            x-on:click="alert=true"
            id="show-alert"
            class="hidden"
        ></button>
    </div>
</template>
