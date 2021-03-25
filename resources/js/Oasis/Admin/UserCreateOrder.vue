<template>
    <div id="single-page">
        <div id="page-content" class="small-width">
            <MobileHeader :title="$router.currentRoute.meta.title" />
            <PageHeader :can-back="true" :title="$router.currentRoute.meta.title" />

            <div class="content-page">
                <ValidationObserver @submit.prevent="createUser" ref="createUser" v-slot="{ invalid }" tag="form" class="form block-form">

                    <div class="form-group">
                        <FormLabel>{{ $t('admin_page_user.create_user.group_details') }}</FormLabel>

                        <!--Email-->
                        <div class="block-wrapper">
                            <label>{{ $t('page_registration.label_email') }}</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Email" rules="required" v-slot="{ errors }">
                                <input v-model="user.email" :placeholder="$t('admin_page_user.create_user.label_email')" type="email" class="focus-border-theme" :class="{'is-error': errors[0]}" />
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <!--Name-->
                        <div class="block-wrapper">
                            <label>ICO:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ICO" rules="required" v-slot="{ errors }">
                                <input v-model="user.ico" placeholder="Type ICO" type="text" class="focus-border-theme" :class="{'is-error': errors[0]}" />
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                <small v-if="fullDetails" class="input-help">
                                    {{ fullDetails }}
                                </small>
                            </ValidationProvider>
                        </div>

                        <!--Name-->
                        <div class="block-wrapper">
                            <label>Name:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="User Name" rules="required" v-slot="{ errors }">
                                <input v-model="user.name" :placeholder="$t('admin_page_user.create_user.label_name')" type="text" class="focus-border-theme" :class="{'is-error': errors[0]}" />
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>


                        <div class="block-wrapper">
                            <label>{{ $t('user_settings.address') }}:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required" name="Address" v-slot="{ errors }">
                                <input v-model="user.address"
                                       :placeholder="$t('user_settings.address_plac')"
                                       type="text"
                                       class="focus-border-theme"
                                       :class="{'is-error': errors[0]}"
                                />
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="wrapper-inline">
                            <div class="block-wrapper">
                                <label>{{ $t('user_settings.city') }}:</label>
                                <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required" name="City" v-slot="{ errors }">
                                    <input v-model="user.city"
                                           :placeholder="$t('user_settings.city_plac')"
                                           type="text"
                                           class="focus-border-theme"
                                           :class="{'is-error': errors[0]}"
                                    />
                                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>

                            <div class="block-wrapper">
                                <label>{{ $t('user_settings.postal_code') }}:</label>
                                <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required" name="Postal Code" v-slot="{ errors }">
                                    <input v-model="user.postal_code"
                                           :placeholder="$t('user_settings.postal_code_plac')"
                                           type="text"
                                           class="focus-border-theme"
                                           :class="{'is-error': errors[0]}"
                                    />
                                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                                </ValidationProvider>
                            </div>
                        </div>

                        <div class="block-wrapper">
                            <label>{{ $t('user_settings.country') }}:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required" name="Country" v-slot="{ errors }">
                                <SelectInput v-model="user.country"
                                             :default="user.country"
                                             :options="countries"
                                             :placeholder="$t('user_settings.country_plac')"
                                             :isError="errors[0]" />
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>{{ $t('user_settings.state') }}:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required" name="State" v-slot="{ errors }">
                                <input v-model="user.state"
                                       :placeholder="$t('user_settings.state_plac')"
                                       type="text"
                                       class="focus-border-theme"
                                       :class="{'is-error': errors[0]}"
                                />
                                <small class="input-help">
                                    State, county, province, or region.
                                </small>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>{{ $t('user_settings.phone_number') }}:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required" name="Phone Number" v-slot="{ errors }">
                                <input v-model="user.phone_number"
                                       :placeholder="$t('user_settings.phone_number_plac')"
                                       type="text"
                                       class="focus-border-theme"
                                       :class="{'is-error': errors[0]}"
                                />
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Plan:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" rules="required" name="Plan" v-slot="{ errors }">
                                <SelectInput v-model="user.plan"
                                             :default="user.country"
                                             :options="plans"
                                             placeholder="Vyberte plan"
                                             :isError="errors[0]" />
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>

                    <div class="form-group">
                        <ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit">
                            {{ $t('admin_page_user.create_user.submit') }}
                        </ButtonBase>
                    </div>
                </ValidationObserver>
            </div>
        </div>
    </div>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import ImageInput from '@/components/Others/Forms/ImageInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageHeader from '@/components/Others/PageHeader'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'
    import {debounce} from "lodash";

    export default {
        name: 'CreateSubscriptionRequest',
        components: {
            ValidationProvider,
            ValidationObserver,
            SectionTitle,
            MobileHeader,
            SelectInput,
            ButtonBase,
            ImageInput,
            PageHeader,
            FormLabel,
            required,
        },
        computed: {
            ...mapGetters([
                'countries'
            ]),
        },
        watch: {
            'user.ico': function (val) {
                this.getCompanyDetails(val)
            }
        },
        data() {
            return {
                isLoading: false,
                plans: [],
                user: {
                    ico: '',
                    name: '',
                    email: '',
                    address: '',
                    city: '',
                    postal_code: '',
                    country: 'CZ',
                    state: 'Česká Republika',
                    phone_number: '',
                    plan: '',
                },
                fullDetails: ''
            }
        },
        methods: {
            async createUser() {

                // Validate fields
                const isValid = await this.$refs.createUser.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                axios
                    .post('/api/oasis/admin/users/create-order', this.user)
                    .then(response => {

                        // Show toaster
                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('toaster.created_user'),
                        })

                        // Go to User page
                        this.$router.push({name: 'UserDetail', params: {id: response.data.data.id}})
                    })
                    .catch(error => {

                        // Validation errors
                        if (error.response.status == 422) {

                            if (error.response.data.errors['email']) {

                                this.$refs.createUser.setErrors({
                                    'email': error.response.data.errors['email']
                                });
                            }
                        } else {

                            events.$emit('alert:open', {
                                title: this.$t('popup_error.title'),
                                message: this.$t('popup_error.message'),
                            })
                        }
                    })
                    .finally(() => {
                        this.isLoading = false
                    })
            },
            getCompanyDetails: debounce(function (value) {
                axios.get('/api/oasis/admin/company-details?ico=' + value)
                    .then(response => {
                        this.user.name = response.data.name
                        this.user.address = response.data.addr_streetnr
                        this.user.city = response.data.city
                        this.user.postal_code = response.data.addr_zip
                        this.fullDetails = response.data.name + ' ' + response.data.addr_full

                        this.$refs.createUser.reset()
                    })
                    .catch(error => {
                        if (error.response.status == 404) {
                            this.$refs.createUser.setErrors({
                                'ICO': 'Nič sa nenašlo :('
                            });
                        }
                    })
            }, 300),
        },
        created() {
            axios.get('/api/admin/plans')
                .then(response => {
                    response.data.data.forEach(plan => {
                        this.plans.push({
                            label: plan.data.attributes.name + ' - ' + plan.data.attributes.capacity_formatted,
                            value: plan.data.id,
                        })
                    })
                })
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';
    @import '@assets/vuefilemanager/_forms';
</style>
