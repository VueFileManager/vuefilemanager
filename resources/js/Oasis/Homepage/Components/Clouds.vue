<template>
    <div class="clouds">
        <slot></slot>
    </div>
</template>

<script>

    export default {
        name: 'Clouds',
    }
</script>

<style lang="scss" scoped>
    @import '@assets/oasis/_components';
    @import '@assets/oasis/_homepage';
    @import '@assets/oasis/_responsive';

    .clouds {
        position: absolute;
        overflow: hidden;
        bottom: 0;
        right: 0;
        left: 0;
        top: 0;
    }

</style>
