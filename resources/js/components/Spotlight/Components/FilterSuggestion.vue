<template>
    <div class="flex cursor-pointer items-center py-2">
        <span
            class="dark-text-theme rounded-lg bg-light-background py-1 px-2 text-sm font-bold dark:bg-4x-dark-foreground"
        >
            {{ keyword }} + {{ $t('space') }}
        </span>
        <p class="ml-3 text-sm font-semibold text-gray-500">
            {{ description }}
        </p>
    </div>
</template>
<script>
export default {
    name: 'FilterSuggestion',
    props: ['keyword', 'description'],
}
</script>
