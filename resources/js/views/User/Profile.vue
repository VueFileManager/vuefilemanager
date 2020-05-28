<template>
    <div class="page-tab">
        <div class="page-tab-group">
            <ValidationObserver ref="account" v-slot="{ invalid }" tag="form" class="form block-form">
                <div class="block-wrapper">
                    <label>{{ $t('page_registration.label_email') }}</label>
                    <div class="input-wrapper">
                        <input :value="app.user.email" :placeholder="$t('page_registration.placeholder_email')"
                               type="email" disabled/>
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
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageHeader from '@/components/Others/PageHeader'
    import ThemeLabel from '@/components/Others/ThemeLabel'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {debounce} from 'lodash'

    export default {
        name: 'Profile',
        components: {
            ValidationProvider,
            ValidationObserver,
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
                name: '',
                isLoading: false,
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

    @media only screen and (max-width: 960px) {

        .form {
            .button-base {
                width: 100%;
                margin-top: 0;
                text-align: center;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

    }

</style>
