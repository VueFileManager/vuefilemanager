<template>
    <div id="app">
        <VueFileManager :config="config" style="height: 100%; width: 100%"/>
    </div>
</template>

<script>
    import VueFileManager from './components/VueFileManager.vue'

    export default {
        name: 'app',
        components: {
            VueFileManager
        },
        data() {
            return {
                config: {
                    //api: 'https://vuefilemanager.hi5ve.digital/api'
                    api: 'http://localhost:8000/api'
                    //api: 'http://172.20.10.5:8000/api'
                    //api: 'http://192.168.1.131:8000/api'
                }
            }
        }
    }
</script>

<style lang="scss">
    @import "@assets/app.scss";

    #app {
        height: 100%;
        width: 100%;
        position: absolute;
    }
</style>
