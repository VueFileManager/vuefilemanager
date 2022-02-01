<template>
    <div class="relative block cursor-pointer lg:mr-2 lg:w-2 2xl:w-3" :style="{ height: bar.percentage + '%' }" @mouseover="isVisible = true" @mouseleave="isVisible = false">
        <div v-if="isVisible" class="absolute -top-4 z-10 ml-1.5 -translate-y-full -translate-x-1/2 transform rounded-lg bg-gray-800 py-2 px-3 shadow-lg dark:bg-white">
            <b class="mb-2 block whitespace-nowrap text-xs text-white dark:text-gray-800">
                {{ bar.created_at }}
            </b>
            <div class="flex items-center pb-1">
                <span class="bg-theme mr-2 block h-3 w-3 rounded"></span>
                <b class="whitespace-nowrap text-xs text-white dark:text-gray-800">
                    {{ bar.amount }}
                </b>
            </div>
            <div class="absolute -bottom-2.5 left-0 right-0 mx-auto inline-block w-5 overflow-hidden">
                <div class="h-3 w-3 origin-top-left -rotate-45 transform bg-gray-800 dark:bg-white"></div>
            </div>
        </div>
        <span class="bg-theme block h-full w-full rounded-lg"></span>
    </div>
</template>
<script>
export default {
    name: 'Bar',
    props: ['bar'],
    data() {
        return {
            isVisible: false,
        }
    },
}
</script>
