<template>
    <div>
        <Spinner/>
    </div>
</template>

<script>
import Spinner from '/resources/js/components/FilesView/Spinner'

export default {
    name: 'SocialiteCallback',
    components: {Spinner},
    created () {
        axios
            .get(`/api${this.$route.fullPath}`)
            .then(() => {

                // Set login state
                this.$store.commit('SET_AUTHORIZED', true)

                // Go to files page
                this.$router.push({name: 'Files'})
            })
            .catch((error) => {
                
                this.$isSomethingWrong()

                this.$router.push({name: 'Homepage'})
            })              
    }
    
}
</script>