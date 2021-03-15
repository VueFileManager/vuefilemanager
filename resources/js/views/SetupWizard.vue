<template>
    <router-view />
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        name: 'SetupWizard',
        computed: {
            ...mapGetters(['config']),
        },
        mounted() {
            if (this.config.installation === 'setup-done')
                this.$router.push({name: 'SignIn'})
        }
    }
</script>
