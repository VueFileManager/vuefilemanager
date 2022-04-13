<template>
    <div :class="{ 'mb-6 sm:mb-7': !isLast }">
        <!--Label for input-->
        <label v-if="title" class="mb-1.5 block text-sm font-bold text-gray-700 dark:text-gray-200">
            {{ title }}:
        </label>

        <!--Form element-->
        <slot />

        <!--Input Description-->
        <span v-if="error" class="pt-2 text-xs dark:text-rose-600 text-rose-600">
            {{ error }}
        </span>

        <!--Input Description-->
        <small v-if="description" class="block pt-2 text-xs leading-4 dark:text-gray-500 text-gray-500" v-html="description"></small>
    </div>
</template>

<script>
export default {
    name: 'AppInputText',
    props: ['description', 'isLast', 'title', 'error'],
}
</script>
