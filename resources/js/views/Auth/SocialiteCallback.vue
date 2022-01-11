<template>
	<Spinner/>
</template>

<script>
import Spinner from '/resources/js/components/FilesView/Spinner'
import {events} from "../../bus";
import i18n from "../../i18n";

export default {
    name: 'SocialiteCallback',
    components: {
		Spinner
	},
    created () {
        axios
            .get(`/api${this.$route.fullPath}`)
            .then(() => {

                // Set login state
                this.$store.commit('SET_AUTHORIZED', true)

                // Go to files page
                this.$router.push({name: 'Files'})
            })
            .catch(error => {
				if (error.response.status === 401) {
					events.$emit('alert:open', {
						title: error.response.data.message,
					})
				} else {
					this.$isSomethingWrong()
				}

                this.$router.push({name: 'SignIn'})
            })              
    }
    
}
</script>