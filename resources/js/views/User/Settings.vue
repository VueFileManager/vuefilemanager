<template>
    <PageTab>
        <PageTabGroup v-if="userInfo">
            <div class="form block-form">
                <FormLabel>{{ $t('user_settings.title_account') }}</FormLabel>
                <div class="block-wrapper">
                    <label>{{ $t('page_registration.label_email') }}</label>
                    <div class="input-wrapper">
                        <input :value="userInfo.email"
                               :placeholder="$t('page_registration.placeholder_email')"
                               type="email"
                               disabled
                        />
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('page_registration.label_name') }}</label>
                    <div class="input-wrapper">
                        <input @keyup="changeUserName"
                               v-model="userInfo.name"
                               :placeholder="$t('page_registration.placeholder_name')"
                               type="text"
                               class="focus-border-theme"
                        />
                    </div>
                </div>
            </div>
        </PageTabGroup>

         <PageTabGroup v-if="userInfo">
            <div class="form block-form">
                <FormLabel>{{$t('user_settings.timezone')}}</FormLabel>
                <div class="block-wrapper">
                    <label>GMT:</label>
                    <div class="input-wrapper">
                        <SelectInput @input="$updateText('/user/relationships/settings', 'timezone', userInfo.timezone)"
                                    v-model="userInfo.timezone"
                                    :default="userInfo.timezone"
                                    :options="timezones"
                                    :placeholder="$t('user_settings.timezone_plac')"/>
                    </div>
                </div>
            </div>
        </PageTabGroup>

        <PageTabGroup v-if="config.isSaaS && billingInfo">
            <div class="form block-form">
                <FormLabel>{{ $t('user_settings.title_billing') }}</FormLabel>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.name') }}:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'name', billingInfo.name)"
                               v-model="billingInfo.name"
                               :placeholder="$t('user_settings.name_plac')"
                               type="text"
                               class="focus-border-theme"
                        />
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.address') }}:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'address', billingInfo.address)"
                               v-model="billingInfo.address"
                               :placeholder="$t('user_settings.address_plac')"
                               type="text"
                               class="focus-border-theme"
                        />
                    </div>
                </div>
                <div class="wrapper-inline">
                    <div class="block-wrapper">
                        <label>{{ $t('user_settings.city') }}:</label>
                        <div class="input-wrapper">
                            <input @keyup="$updateText('/user/relationships/settings', 'city', billingInfo.city)"
                                   v-model="billingInfo.city"
                                   :placeholder="$t('user_settings.city_plac')"
                                   type="text"
                                   class="focus-border-theme"
                            />
                        </div>
                    </div>
                    <div class="block-wrapper">
                        <label>{{ $t('user_settings.postal_code') }}:</label>
                        <div class="input-wrapper">
                            <input @keyup="$updateText('/user/relationships/settings', 'postal_code', billingInfo.postal_code)"
                                   v-model="billingInfo.postal_code"
                                   :placeholder="$t('user_settings.postal_code_plac')"
                                   type="text"
                                   class="focus-border-theme"
                            />
                        </div>
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.country') }}:</label>
                    <div class="input-wrapper">
                        <SelectInput @input="$updateText('/user/relationships/settings', 'country', billingInfo.country)"
                                     v-model="billingInfo.country"
                                     :default="billingInfo.country"
                                     :options="countries"
                                     :placeholder="$t('user_settings.country_plac')"
                        />
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.state') }}:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'state', billingInfo.state)"
                               v-model="billingInfo.state"
                               :placeholder="$t('user_settings.state_plac')"
                               type="text"
                               class="focus-border-theme"
                        />
                        <small class="input-help">
                            State, county, province, or region.
                        </small>
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.phone_number') }}:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'phone_number', billingInfo.phone_number)"
                               v-model="billingInfo.phone_number"
                               :placeholder="$t('user_settings.phone_number_plac')"
                               type="text"
                               class="focus-border-theme"
                        />
                    </div>
                </div>
            </div>
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageTab from '@/components/Others/Layout/PageTab'
    import PageHeader from '@/components/Others/PageHeader'
    import ThemeLabel from '@/components/Others/ThemeLabel'
    import {required} from 'vee-validate/dist/rules'
    import { mapGetters } from 'vuex'
    import {debounce} from 'lodash'

    export default {
        name: 'Settings',
        props: [
            'user'
        ],
        components: {
            ValidationProvider,
            ValidationObserver,
            PageTabGroup,
            MobileHeader,
            SelectInput,
            PageHeader,
            ButtonBase,
            ThemeLabel,
            FormLabel,
            required,
            PageTab,
        },
        computed: {
            ...mapGetters(['config', 'countries', 'timezones']),
        },
        data() {
            return {
                userInfo: undefined,
                billingInfo: undefined,
                isLoading: false,
            }
        },
        methods: {
            changeUserName() {
                this.$store.commit('UPDATE_NAME', this.userInfo.name)
                this.$updateText('/user/profile', 'name', this.userInfo.name)
            }
        },
        created() {

            this.userInfo = {
                timezone: this.user.data.relationships.settings.data.attributes.timezone,
                name: this.user.data.relationships.settings.data.attributes.name,
                email: this.user.data.attributes.email
            }

            this.billingInfo = {
                name: this.user.data.relationships.settings.data.attributes.name,
                address: this.user.data.relationships.settings.data.attributes.address,
                state: this.user.data.relationships.settings.data.attributes.state,
                city: this.user.data.relationships.settings.data.attributes.city,
                postal_code: this.user.data.relationships.settings.data.attributes.postal_code,
                country: this.user.data.relationships.settings.data.attributes.country,
                phone_number: this.user.data.relationships.settings.data.attributes.phone_number,
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';
    @import '@assets/vuefilemanager/_forms';

    .block-form {
        max-width: 100%;
    }

</style>
