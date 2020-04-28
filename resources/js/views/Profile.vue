<template>
    <div id="user-settings">

        <PageHeader :title="$t('profile.page_title')" />

        <div class="content-page">
            <div class="avatar-upload">
                <UserImageInput
                        v-model="avatar"
                        :avatar="app.user.avatar"
                />
                <div class="info">
                    <span class="description">{{ $t('profile.photo_description') }}</span>
                    <span class="supported">{{ $t('profile.photo_supported') }}</span>
                </div>
            </div>

            <ValidationObserver ref="account" v-slot="{ invalid }" tag="form" class="form block-form">

                <ThemeLabel>{{ $t('profile.profile_info') }}</ThemeLabel>
                <div class="block-wrapper">
                    <label>{{ $t('page_registration.label_email') }}</label>
                    <div class="input-wrapper">
                        <input :value="app.user.email" :placeholder="$t('page_registration.placeholder_email')" type="email" disabled/>
                    </div>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('page_registration.label_name') }}</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Full Name" rules="required"
                                        v-slot="{ errors }">
                        <input @keyup="$updateText('/user/profile', 'name', name)" v-model="name"
                               :placeholder="$t('page_registration.placeholder_name')" type="text"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
            </ValidationObserver>

            <ValidationObserver ref="password" @submit.prevent="resetPassword" v-slot="{ invalid }" tag="form"
                                class="form block-form">

                <ThemeLabel>{{ $t('profile.change_pass') }}</ThemeLabel>

                <div class="block-wrapper">
                    <label>{{ $t('page_create_password.label_new_pass') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="New Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="newPassword" :placeholder="$t('page_create_password.label_new_pass')" type="password"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>{{ $t('page_create_password.label_confirm_pass') }}:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Confirm Your Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="newPasswordConfirmation" :placeholder="$t('page_create_password.label_confirm_pass')" type="password"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <ButtonBase type="submit" button-style="theme" class="confirm-form">
                        {{ $t('profile.store_pass') }}
                    </ButtonBase>
                </div>
            </ValidationObserver>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import UserImageInput from '@/components/Others/UserImageInput'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageHeader from '@/components/Others/PageHeader'
    import ThemeLabel from '@/components/Others/ThemeLabel'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {debounce} from 'lodash'
    import {events} from '@/bus'
    import axios from 'axios'

    export default {
        name: 'UserSettings',
        components: {
            ValidationProvider,
            ValidationObserver,
            UserImageInput,
            PageHeader,
            ButtonBase,
            ThemeLabel,
            required,
        },
        computed: {
            ...mapGetters(['app']),
        },
        watch: {
            name: debounce(function (val) {
                if (val === '') return

                this.$store.commit('UPDATE_NAME', val)
            }, 300),
        },
        data() {
            return {
                newPasswordConfirmation: '',
                newPassword: '',
                avatar: undefined,
                name: '',
            }
        },
        methods: {
            async resetPassword() {

                // Validate fields
                const isValid = await this.$refs.password.validate();

                if (!isValid) return;

                // Send request to get user reset link
                axios
                    .post(this.$store.getters.api + '/user/password', {
                            password: this.newPassword,
                            password_confirmation: this.newPasswordConfirmation,
                        })
                    .then(() => {

                        // Reset inputs
                        this.newPassword = ''
                        this.newPasswordConfirmation = ''

                        // Reset errors
                        this.$refs.password.reset()

                        // Show error message
                        events.$emit('success:open', {
                            title: this.$t('popup_pass_changed.title'),
                            message: this.$t('popup_pass_changed.message'),
                        })
                    })
                    .catch(error => {

                        if (error.response.status == 422) {

                            if (error.response.data.errors['password']) {

                                this.$refs.password.setErrors({
                                    'New Password': error.response.data.errors['password']
                                });
                            }
                        }
                    })
            }
        },
        created() {
            this.name = this.app.user.name
            this.avatar = this.app.user.avatar
        }
    }
</script>

<style lang="scss">
    @import "@assets/app.scss";
    @import '@assets/vue-file-manager/_forms';

    .avatar-upload {
        display: flex;
        align-items: center;
        margin-top: 20px;

        .info {
            margin-left: 25px;

            .description {
                @include font-size(18);
                font-weight: 700;
                color: $text;
            }

            .supported {
                display: block;
                @include font-size(12);
                font-weight: 500;
                color: $light_text;
            }
        }
    }

    .form {

        .confirm-form {
            margin-top: 30px;
            text-align: right;
        }

        &.block-form {
            margin-top: 50px;
            max-width: 700px;

            .block-wrapper {
                justify-content: flex-start;

                label {
                    text-align: left;
                    flex: 0 0 220px;
                }

                .input-wrapper, input {
                    width: 100%;
                }
            }
        }
    }

    #user-settings {
        overflow: hidden;
        width: 100%;
        height: 100%;
        position: relative;

        .content-page {
            padding-left: 30px;
            padding-right: 30px;
            overflow-y: auto;
            height: 100%;
            padding-bottom: 100px;
        }
    }

    @media only screen and (max-width: 960px) {

        #user-settings {

            .content-page {
                padding-left: 15px;
                padding-right: 15px;
            }
        }

        .avatar-upload {
            margin-top: 30px;
        }

        .form {
            .button-base {
                width: 100%;
                margin-top: 0;
                text-align: center;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .avatar-upload .info {

            .description {
                color: $dark_mode_text_primary;
            }

            .supported {
                color: $dark_mode_text_secondary;
            }
        }
    }

</style>
