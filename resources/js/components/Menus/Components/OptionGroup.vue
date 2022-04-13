<template>
    <div>
        <b v-if="title" class="dark-text-theme mt-2 block py-0.5 px-4 text-xs text-gray-400">
            {{ title }}
        </b>
        <ul class="option-group py-1">
            <slot />
        </ul>
    </div>
</template>

<script>
export default {
    name: 'OptionGroup',
    props: ['title'],
}
</script>
