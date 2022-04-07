<template>
    <svg
        class="alphabet-icon"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        fill-rule="evenodd"
        stroke-linecap="round"
        stroke-linejoin="round"
        :width="`${size}px`"
        :height="`${size}px`"
        viewBox="-2 0 15 15"
        version="1.1"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
    >
        <polyline
            id="Path"
            points="11.1999993 13.1999991 5.59999967 0.199999094 0 13.1999991 5.59999967 0.199999094"
        ></polyline>
        <line x1="2.25" y1="8" x2="8.75" y2="8" id="Line-2"></line>
    </svg>
</template>

<script>
export default {
    props: ['size'],
}
</script>

<style lang="scss">
.alphabet-icon {
    polyline,
    line,
    g {
        color: inherit;
    }
}
</style>
