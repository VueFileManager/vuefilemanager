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
        	let status = this.$root.$data.config.installation

            if (status && status === 'setup-done')
                this.$router.push({name: 'SignIn'})
        }
    }
</script>
