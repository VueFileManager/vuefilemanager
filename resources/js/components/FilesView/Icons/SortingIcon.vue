<template>
    <svg
        class="preview-list-icon"
        fill="none"
        stroke="currentColor"
        stroke-width="1.5"
        fill-rule="evenodd"
        stroke-linecap="round"
        stroke-linejoin="round"
        width="15px"
        height="15px"
        viewBox="0 0 20 16"
        version="1.1"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
    >
        <rect x="9.77777778" y="0" width="6.22222222" height="6.22222222"></rect>
        <rect x="9.77777778" y="9.77777778" width="6.22222222" height="6.22222222"></rect>
        <line x1="0" y1="2" x2="6" y2="2"></line>
        <line x1="0" y1="8" x2="6" y2="8"></line>
        <line x1="0" y1="14" x2="6" y2="14"></line>
    </svg>
</template>

<script>
export default {
    name: 'SortingIcon',
}
</script>

<style lang="scss">
.preview-list-icon {
    rect,
    line {
        color: inherit;
    }
}
</style>
