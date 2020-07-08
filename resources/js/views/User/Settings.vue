<template>
    <PageTab>
        <PageTabGroup v-if="userInfo">
            <div class="form block-form">
                <FormLabel>Account Information</FormLabel>
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
        <PageTabGroup v-if="config.isSaaS && billingInfo">
            <div class="form block-form">
                <FormLabel>Billing Information</FormLabel>
                <div class="block-wrapper">
                    <label>Name:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'billing_name', billingInfo.billing_name)"
                               v-model="billingInfo.billing_name"
                               placeholder="Type your billing name"
                               type="text"
                        />
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>Address:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'billing_address', billingInfo.billing_address)"
                               v-model="billingInfo.billing_address"
                               placeholder="Type your billing address"
                               type="text"
                        />
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>State:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'billing_state', billingInfo.billing_state)"
                               v-model="billingInfo.billing_state"
                               placeholder="Type your billing state"
                               type="text"
                        />
                    </div>
                </div>
                <div class="wrapper-inline">
                    <div class="block-wrapper">
                        <label>City:</label>
                        <div class="input-wrapper">
                            <input @keyup="$updateText('/user/relationships/settings', 'billing_city', billingInfo.billing_city)"
                                   v-model="billingInfo.billing_city"
                                   placeholder="Type your billing city"
                                   type="text"
                            />
                        </div>
                    </div>
                    <div class="block-wrapper">
                        <label>Postal Code:</label>
                        <div class="input-wrapper">
                            <input @keyup="$updateText('/user/relationships/settings', 'billing_postal_code', billingInfo.billing_postal_code)"
                                   v-model="billingInfo.billing_postal_code"
                                   placeholder="Type your billing postal code"
                                   type="text"
                            />
                        </div>
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>Country:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'billing_country', billingInfo.billing_country)"
                               v-model="billingInfo.billing_country"
                               placeholder="Type your billing country"
                               type="text"
                        />
                    </div>
                </div>
                <div class="block-wrapper">
                    <label>Phone Number:</label>
                    <div class="input-wrapper">
                        <input @keyup="$updateText('/user/relationships/settings', 'billing_phone_number', billingInfo.billing_phone_number)"
                               v-model="billingInfo.billing_phone_number"
                               placeholder="Type your billing phone number"
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
            PageTabGroup,
            FormLabel,
            PageTab,
            ValidationProvider,
            ValidationObserver,
            MobileHeader,
            PageHeader,
            ButtonBase,
            ThemeLabel,
            required,
        },
        computed: {
            ...mapGetters(['config']),
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

    @media only screen and (max-width: 960px) {

    }

    @media (prefers-color-scheme: dark) {

    }

</style>
