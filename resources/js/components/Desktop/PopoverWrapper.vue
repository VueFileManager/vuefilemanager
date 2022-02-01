<template>
    <div class="popover-wrapper">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'PopoverWrapper',
}
</script>

<style scoped lang="scss">
.popover-wrapper {
    position: relative;
}
</style>
