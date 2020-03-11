<template>
    <div id="app">
        <VueFileManager style="height: 100%; width: 100%"/>
    </div>
</template>

<script>
    import VueFileManager from './components/VueFileManager.vue'

    export default {
        name: 'app',
        components: {
            VueFileManager
        },
    }
</script>

<style lang="scss">

    #app {
        height: 100%;
        width: 100%;
        position: absolute;
    }
</style>
