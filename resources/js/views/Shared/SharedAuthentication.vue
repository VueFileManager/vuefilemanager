<template>
    <div id="password-view">
        <AuthContent class="center" name="password" :visible="true">
            <img v-if="config.app_logo" class="logo" :src="$getImage(config.app_logo)" :alt="config.app_name">
            <b v-if="! config.app_logo" class="auth-logo-text">{{ config.app_name }}</b>

            <h1>{{ $t('page_shared.title') }}</h1>
            <h2>{{ $t('page_shared.subtitle') }}</h2>

            <ValidationObserver @submit.prevent="authenticateProtected" ref="authenticateProtected" v-slot="{ invalid }" tag="form" class="form inline-form">

                <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Password" rules="required" v-slot="{ errors }">
                    <input v-model="password" :placeholder="$t('page_shared.placeholder_pass')" type="password" :class="{'is-error': errors[0]}" />
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>

                <AuthButton icon="chevron-right" :text="$t('page_shared.submit')" :loading="isLoading" :disabled="isLoading" />
            </ValidationObserver>
        </AuthContent>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContent from '/resources/js/components/Auth/AuthContent'
    import AuthButton from '/resources/js/components/Auth/AuthButton'
    import axios from "axios";
    import {mapGetters} from "vuex";

    export default {
        name: 'SharedAuthentication',
        components: {
            ValidationObserver,
            ValidationProvider,
            AuthContent,
            AuthButton,
        },
        computed: {
            ...mapGetters([
                'config',
            ]),
        },
        data() {
            return {
                password: '',
                isLoading: false,
            }
        },
        methods: {
            async authenticateProtected() {

                // Validate fields
                const isValid = await this.$refs.authenticateProtected.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get verify account
                axios
                    .post('/api/browse/authenticate/' + this.$route.params.token, {
                        password: this.password
                    })
                    .then(response => {

                        // Show file browser
                        if (response.data.data.attributes.type === 'folder' && this.$router.currentRoute.name !== 'Public') {
							this.$router.replace({name: 'Public', params: {token: this.$route.params.token, id: response.data.data.attributes.item_id}})
						}

                        // Show single file
                        if (response.data.data.attributes.type !== 'folder' && this.$router.currentRoute.name !== 'SharedSingleFile') {
                            this.$router.push({name: 'SharedSingleFile'})
                        }
                    })
                    .catch(error => {

                        if (error.response.status === 401)
                            this.$refs.authenticateProtected.setErrors({
                                'Password': [error.response.data]
                            });
                    })
                    .finally(() => {
                        this.isLoading = false
                    })
            },
        },
    }
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';
    @import '/resources/sass/vuefilemanager/_auth-form';
    @import '/resources/sass/vuefilemanager/_auth';

    #password-view {
        width: 100%;
        display: grid;
        height: inherit;

        .center {
            margin: auto;
        }
    }
</style>