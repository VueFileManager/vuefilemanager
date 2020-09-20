<template>
    <div id="single-page">
        <div id="page-content" class="small-width">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :can-back="true" :title="$router.currentRoute.meta.title"/>

            <div class="content-page">
                <ValidationObserver @submit.prevent="createPlan" ref="createPlan" v-slot="{ invalid }" tag="form" class="form block-form form-fixed-width">

                    <div class="form-group">
                        <FormLabel>
                            {{ $t('admin_page_plans.form.title_details') }}
                        </FormLabel>

                        <!--Name-->
                        <div class="block-wrapper">
                            <label>{{ $t('admin_page_plans.form.name') }}:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Name" rules="required" v-slot="{ errors }">
                                <input v-model="plan.name" :placeholder="$t('admin_page_plans.form.name_plac')" type="text" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <!--Description-->
                        <div class="block-wrapper">
                            <label>{{ $t('admin_page_plans.form.description') }}:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Description" v-slot="{ errors }">
                                <textarea v-model="plan.description" :placeholder="$t('admin_page_plans.form.description_plac')" :class="{'is-error': errors[0]}"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <FormLabel>
                            {{ $t('admin_page_plans.form.title_pricing') }}
                        </FormLabel>


                        <!--Price-->
                        <div class="block-wrapper">
                            <label>{{ $t('admin_page_plans.form.price') }}:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Plan price" rules="required" v-slot="{ errors }">
                                <input v-model="plan.price" :placeholder="$t('admin_page_plans.form.price_plac')" type="number" step="0.01" min="1" max="999999999999"
                                       :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <!--Storage Capacity-->
                        <div class="block-wrapper">
                            <label>{{ $t('admin_page_plans.form.storage') }}:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Storage capacity" rules="required" v-slot="{ errors }">
                                <input v-model="plan.capacity" :placeholder="$t('admin_page_plans.form.storage_plac')" type="number" min="1" max="999999999"
                                       :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                            <small class="input-help">
                                {{ $t('admin_page_plans.form.storage_helper') }}
                            </small>
                        </div>
                    </div>

                    <div class="form-group" v-if="isError">
                        <InfoBox type="error" style="margin-top: 40px">
                            <p>{{ errorMessage }}</p>
                        </InfoBox>
                    </div>

                    <div class="form-group">
                        <ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit">
                            {{ $t('admin_page_plans.create_plan_button') }}
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
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import SectionTitle from '@/components/Others/SectionTitle'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import PageHeader from '@/components/Others/PageHeader'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import {required} from 'vee-validate/dist/rules'
    import {mapGetters} from 'vuex'
    import {events} from "@/bus"
    import axios from 'axios'

    export default {
        name: 'PlanCreate',
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
            InfoBox,
        },
        data() {
            return {
                isLoading: false,
                errorMessage: '',
                isError: false,
                plan: {
                    name: '',
                    price: '',
                    capacity: '',
                    description: '',
                },
            }
        },
        methods: {
            async createPlan() {

                // Validate fields
                const isValid = await this.$refs.createPlan.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get user token
                axios
                    .post('/api/plans/store', {
                        attributes: this.plan
                    })
                    .then(response => {

                        // Show toaster
                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('toaster.plan_created'),
                        })

                        // Go to User page
                        this.$router.push({name: 'PlanSettings', params: {id: response.data.data.id}})
                    })
                    .catch(error => {

                        // Validation errors
                        if (error.response.status == 422) {

                            if (error.response.data.errors['storage_capacity']) {
                                this.$refs.createPlan.setErrors({
                                    'storage capacity': this.$t('errors.capacity_digit')
                                });
                            }
                        }

                        if (error.response.status == 500) {
                            this.isError = true
                            this.errorMessage = error.response.data.message
                        }

                    }).finally(() => {

                        // End loading
                        this.isLoading = false
                })
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';
</style>
