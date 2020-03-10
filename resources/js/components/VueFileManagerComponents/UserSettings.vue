<template>
    <div id="user-settings">

        <PageHeader title="User Profile" />

        <div class="content-page">
            <div class="avatar-upload">
                <UserImageInput
                        v-model="avatar"
                        :avatar="app.user.avatar"
                />
                <div class="info">
                    <span class="description">Change Your Profile Picture</span>
                    <span class="supported">Supported formats are .png, .jpg, .jpeg.</span>
                </div>
            </div>

            <ValidationObserver ref="account" v-slot="{ invalid }" tag="form" class="form block-form">

                <ThemeLabel>Profile Information</ThemeLabel>
                <div class="block-wrapper">
                    <label>Email:</label>
                    <div class="input-wrapper">
                        <input :value="app.user.email" placeholder="Type your E-mail" type="email" disabled/>
                    </div>
                </div>

                <div class="block-wrapper">
                    <label>Full Name:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Full Name" rules="required"
                                        v-slot="{ errors }">
                        <input @keyup="$updateText('/user/profile', 'name', name)" v-model="name"
                               placeholder="Type your full name" type="text"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
            </ValidationObserver>

            <ValidationObserver ref="password" @submit.prevent="resetPassword" v-slot="{ invalid }" tag="form"
                                class="form block-form">

                <ThemeLabel>Change Password</ThemeLabel>

                <div class="block-wrapper">
                    <label>Your Password:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="New Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="newPassword" placeholder="New Password" type="password"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <label>Repeat Your Password:</label>
                    <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Confirm Your Password"
                                        rules="required" v-slot="{ errors }">
                        <input v-model="newPasswordConfirmation" placeholder="Confirm your new password" type="password"
                               :class="{'is-error': errors[0]}"/>
                        <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>

                <div class="block-wrapper">
                    <ButtonBase type="submit" button-style="theme" class="confirm-form">
                        Store New Password
                    </ButtonBase>
                </div>
            </ValidationObserver>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import UserImageInput from '@/components/VueFileManagerComponents/Others/UserImageInput'
    import ButtonBase from '@/components/VueFileManagerComponents/FilesView/ButtonBase'
    import PageHeader from '@/components/VueFileManagerComponents/Others/PageHeader'
    import ThemeLabel from '@/components/VueFileManagerComponents/Others/ThemeLabel'
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

                // Start loading
                //this.isLoading = true

                // Send request to get user reset link
                axios
                    .post(this.$store.getters.api + '/user/password', {
                            password: this.newPassword,
                            password_confirmation: this.newPasswordConfirmation,
                        },
                        {
                            headers: {
                                'Authorization': 'Bearer ' + this.$store.getters.token
                            }
                        })
                    .then(() => {

                        // Reset inputs
                        this.newPassword = ''
                        this.newPasswordConfirmation = ''

                        // Reset errors
                        this.$refs.password.reset()

                        // Show error message
                        events.$emit('success:open', {
                            title: 'Your password was changed!',
                            message: 'So now, you have awesome new password.',
                        })

                        // End loading
                        //this.isLoading = false
                    })
                    .catch(error => {

                        if (error.response.status == 422) {

                            if (error.response.data.errors['password']) {

                                this.$refs.password.setErrors({
                                    'New Password': error.response.data.errors['password']
                                });
                            }
                        }

                        // End loading
                        //this.isLoading = false
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


    .avatar-upload {
        display: flex;
        align-items: center;
        margin-top: 20px;

        .info {
            margin-left: 25px;

            .description {
                @include font-size(18);
                font-weight: 700;
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

            .supported {
                color: $dark_mode_text_secondary;
            }
        }
    }

</style>
