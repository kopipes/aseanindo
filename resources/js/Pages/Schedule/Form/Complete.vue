<template>
     <div class="bg-white rounded-md shadow-sm px-3 py-2 mt-3">
          <p class="text-[13px] mb-3">
               To return to the main menu, please click the button below:
          </p>
          <button type="button" @click="$router.go()" class="w-full bg-yellow text-white rounded-md px-3 py-2 text-[12px]">
               Menu
          </button>
     </div>
</template>