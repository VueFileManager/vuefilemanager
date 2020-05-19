<template>
    <div id="auth">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        name: 'AuthContentWrapper',
    }
</script>

<style scoped lang="scss">

    #auth {
        height: 100%;
        width: 100%;
        display: table;
    }
</style>
