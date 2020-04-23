<template>
    <AuthContentWrapper ref="auth">

        <!--Verify share link by password-->
        <AuthContent name="password" :visible="true">

        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import AuthContentWrapper from '@/components/VueFileManagerComponents/Auth/AuthContentWrapper'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContent from '@/components/VueFileManagerComponents/Auth/AuthContent'
    import AuthButton from '@/components/VueFileManagerComponents/Auth/AuthButton'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'SharedContent',
        components: {
            AuthContentWrapper,
            ValidationProvider,
            ValidationObserver,
            AuthContent,
            AuthButton,
            required,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                checkedAccount: undefined,
                password: 'tvojpenis',
                isLoading: false,
            }
        },
        methods: {
            async sharedProtected() {

                // Validate fields
                const isValid = await this.$refs.sharedProtected.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get verify account
                axios
                    .post('/api/shared/authenticate/' + this.$route.params.token, {
                        password: this.password
                    })
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        // Commit shared item options
                        this.$store.commit('SET_PERMISSION', response.data.permission)

                        // Redirect to file browser page
                        this.$router.push({name: 'SharedContent', params: {token: this.$route.params.token}})
                    })
                    .catch(error => {

                        if (error.response.status == 401) {

                            this.$refs.sharedProtected.setErrors({
                                'Password': [error.response.data.message]
                            });
                        }

                        // End loading
                        this.isLoading = false
                    })
            },
        },
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";
    @import '@assets/vue-file-manager/_forms';
    @import '@assets/vue-file-manager/_auth';
</style>
