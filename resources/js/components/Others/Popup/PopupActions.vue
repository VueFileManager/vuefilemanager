<template>
    <div class="actions flex items-center space-x-4 md:p-6 p-4">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: 'PopupActions',
    }
</script>

<style lang="scss" scoped>

    @media only screen and (max-width: 690px) {
        .actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
    }

    @media only screen and (max-width: 320px) {
        .actions {
            position: unset;
        }
    }
</style>
