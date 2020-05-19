<template>
    <div id="user-settings" v-if="app">

        <MobileHeader />
        <PageHeader :title="$router.currentRoute.meta.title" />

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
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import UserImageInput from '@/components/Others/UserImageInput'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageHeader from '@/components/Others/PageHeader'
    import ThemeLabel from '@/components/Others/ThemeLabel'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {debounce} from 'lodash'
    import {events} from '@/bus'
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            ValidationProvider,
            ValidationObserver,
            UserImageInput,
            MobileHeader,
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
                avatar: undefined,
                name: '',
            }
        },
        created() {
            if (this.app) {
                this.name = this.app.user.name
                this.avatar = this.app.user.avatar
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .avatar-upload {
        display: flex;
        align-items: center;
        margin-bottom: 30px;

        .info {
            margin-left: 25px;

            .description {
                @include font-size(15);
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

    #user-settings {
        overflow: hidden;
        width: 100%;
        height: 100%;
        position: relative;

        .content-page {
            overflow-y: auto;
            height: 100%;
            padding-bottom: 100px;
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
        }
    }

    @media only screen and (max-width: 960px) {

        #user-settings {

            .content-page {
                padding-left: 15px;
                padding-right: 15px;
            }
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
