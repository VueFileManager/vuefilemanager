<template>
    <b class="color-label inline-block rounded-lg py-1 px-2 text-xs font-bold capitalize" :class="color">
        <slot />
    </b>
</template>

<script>
export default {
    name: 'ColorLabel',
    props: ['color'],
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';

.color-label {
    &.purple {
        color: $purple;
        background: rgba($purple, 0.1);
    }

    &.yellow {
        color: $yellow;
        background: rgba($yellow, 0.1);
    }

    &.green {
        color: $theme;
        background: rgba($theme, 0.1);
    }

    &.red {
        color: $danger;
        background: rgba($danger, 0.1);
    }
}
</style>
