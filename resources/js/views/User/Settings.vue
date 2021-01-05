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
                        <SelectInput @input="$updateText('/user/relationships/settings', 'timezone', userTimezone)"
                                    v-model="userTimezone"
                                    :default="userTimezone"
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
                        <input @keyup="$updateText('/user/relationships/settings', 'billing_name', billingInfo.billing_name)"
                               v-model="billingInfo.billing_name"
                               :placeholder="$t('user_settings.name_plac')"
                               type="text"
                        />
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.address') }}:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'billing_address', billingInfo.billing_address)"
                               v-model="billingInfo.billing_address"
                               :placeholder="$t('user_settings.address_plac')"
                               type="text"
                        />
                    </div>
                </div>
                <div class="wrapper-inline">
                    <div class="block-wrapper">
                        <label>{{ $t('user_settings.city') }}:</label>
                        <div class="input-wrapper">
                            <input @keyup="$updateText('/user/relationships/settings', 'billing_city', billingInfo.billing_city)"
                                   v-model="billingInfo.billing_city"
                                   :placeholder="$t('user_settings.city_plac')"
                                   type="text"
                            />
                        </div>
                    </div>
                    <div class="block-wrapper">
                        <label>{{ $t('user_settings.postal_code') }}:</label>
                        <div class="input-wrapper">
                            <input @keyup="$updateText('/user/relationships/settings', 'billing_postal_code', billingInfo.billing_postal_code)"
                                   v-model="billingInfo.billing_postal_code"
                                   :placeholder="$t('user_settings.postal_code_plac')"
                                   type="text"
                            />
                        </div>
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.country') }}:</label>
                    <div class="input-wrapper">
                        <SelectInput @input="$updateText('/user/relationships/settings', 'billing_country', billingInfo.billing_country)"
                                     v-model="billingInfo.billing_country"
                                     :default="billingInfo.billing_country"
                                     :options="countries"
                                     :placeholder="$t('user_settings.country_plac')"/>
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.state') }}:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'billing_state', billingInfo.billing_state)"
                               v-model="billingInfo.billing_state"
                               :placeholder="$t('user_settings.state_plac')"
                               type="text"
                        />
                        <small class="input-help">
                            State, county, province, or region.
                        </small>
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>{{ $t('user_settings.phone_number') }}:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'billing_phone_number', billingInfo.billing_phone_number)"
                               v-model="billingInfo.billing_phone_number"
                               :placeholder="$t('user_settings.phone_number_plac')"
                               type="text"
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
                userTimezone: undefined,
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

            this.userTimezone = this.user.relationships.timezone.data.attributes.timezone

            this.userInfo = {
                name: this.user.data.attributes.name,
                email: this.user.data.attributes.email
            }

            this.billingInfo = {
                billing_name: this.user.relationships.settings.data.attributes.billing_name,
                billing_address: this.user.relationships.settings.data.attributes.billing_address,
                billing_state: this.user.relationships.settings.data.attributes.billing_state,
                billing_city: this.user.relationships.settings.data.attributes.billing_city,
                billing_postal_code: this.user.relationships.settings.data.attributes.billing_postal_code,
                billing_country: this.user.relationships.settings.data.attributes.billing_country,
                billing_phone_number: this.user.relationships.settings.data.attributes.billing_phone_number,
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .block-form {
        max-width: 100%;
    }

</style>
