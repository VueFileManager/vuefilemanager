<template>
    <router-view />
</template>

<script>
export default {
    name: 'SetupWizard',
    mounted() {
        let status = config.installation

        if (status && status === 'installation-done') this.$router.push({ name: 'SignIn' })
    },
}
</script>
