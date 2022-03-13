<template>
    <div :class="{ 'mb-6 sm:mb-7': !isLast }" class="flex w-full items-center justify-between space-x-2 sm:space-x-8">
        <!--Label for input-->
        <div class="leading-5">
            <label class="mb-1.5 block text-sm font-bold text-gray-700 dark:text-gray-200"> {{ title }}: </label>

            <!--Input Description-->
            <span v-if="description" class="block text-xs leading-4 dark:text-gray-500 text-gray-500" v-html="description"></span>

            <!--Input Description-->
            <span v-if="error" class="pt-2 text-xs dark:text-rose-600 text-rose-600">
                {{ error }}
            </span>
        </div>

        <!--Form element-->
        <div>
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    name: 'AppInputSwitch',
    props: ['description', 'isLast', 'title', 'error'],
}
</script>
