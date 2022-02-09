<template>
    <router-view />
</template>

<script>
	export default {
		name: 'SetupWizard',
		mounted() {
			let status = this.$root.$data.config.installation

			//if (status && status === 'setup-done') this.$router.push({ name: 'SignIn' })
		},
	}
</script>
