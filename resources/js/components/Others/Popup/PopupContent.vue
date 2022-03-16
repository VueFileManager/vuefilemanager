<template>
    <div
        :class="type"
        class="absolute top-16 bottom-24 left-0 right-0 h-auto overflow-auto px-6 md:relative md:top-0 md:bottom-0"
    >
        <slot />
    </div>
</template>

<script>
export default {
    name: 'PopupContent',
    props: ['type'],
}
</script>
