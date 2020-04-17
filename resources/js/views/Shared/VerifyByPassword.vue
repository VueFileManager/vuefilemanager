<template>
    <AuthContentWrapper ref="auth">

        <!--Verify share link by password-->
        <AuthContent name="password" :visible="true">
            <img class="logo" :src="config.app_logo" :alt="config.app_name">
            <h1>Your Share Link is Protected</h1>
            <h2>Please type the password to get shared content:</h2>

            <ValidationObserver @submit.prevent="sharedProtected" ref="sharedProtected" v-slot="{ invalid }" tag="form" class="form inline-form">

                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="E-Mail" rules="required" v-slot="{ errors }">
                    <input v-model="password" placeholder="Type password" type="password" :class="{'is-error': errors[0]}"/>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton icon="chevron-right" text="Submit" :loading="isLoading" :disabled="isLoading" />
            </ValidationObserver>
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
                isLoading: false,
                password: '',
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
                    .post('/api/share/check', {
                        password: this.password,
                        token: this.$route.query.token
                    })
                    .then(response => {

                        // End loading
                        this.isLoading = false

                        console.log(response.data);
                    })
                    .catch(error => {

                        // todo: catch error

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
