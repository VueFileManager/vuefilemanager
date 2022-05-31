<template>
    <div
        :class="{ 'mb-6 sm:mb-7': !isLast }"
        class="w-full justify-between space-y-4 sm:flex sm:space-x-8 sm:space-x-2 sm:space-y-0"
    >
        <!--Label for input-->
        <div class="leading-5">
            <label class="mb-1.5 block text-sm font-bold text-gray-700 dark:text-gray-200"> {{ title }}: </label>

            <!--Input Description-->
            <span v-if="description" class="block text-xs leading-4 dark:text-gray-500 text-gray-500" v-html="description"></span>

            <!--Input Description-->
            <span v-if="error" class="text-left text-xs text-red-600">
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
    name: 'AppInputButton',
    props: ['description', 'isLast', 'title', 'error'],
}
</script>
