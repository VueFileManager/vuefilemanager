<template>
    <router-view class="dark:bg-dark-background bg-light-background" />
</template>

<script>
export default {
    name: 'SetupWizard',
    mounted() {
        let status = this.$root.$data.config.installation

        if (status && status === 'installation-done') this.$router.push({ name: 'SignIn' })
    },
}
</script>
