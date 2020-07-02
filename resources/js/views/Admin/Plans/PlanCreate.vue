<template>
    <div id="single-page">
        <div id="page-content" class="small-width">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <!--<PageHeader :can-back="true" :title="$router.currentRoute.meta.title"/>-->

            <div class="content-page">
                <ValidationObserver @submit.prevent="createPlan" ref="createPlan" v-slot="{ invalid }" tag="form" class="form block-form form-fixed-width">

                    <div class="form-group">
                        <FormLabel>Plan Details</FormLabel>

                        <!--Name-->
                        <div class="block-wrapper">
                            <label>Name:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Name" rules="required" v-slot="{ errors }">
                                <input v-model="plan.name" placeholder="Plan name" type="text" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <!--Description-->
                        <div class="block-wrapper">
                            <label>Description (optional):</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Description" v-slot="{ errors }">
                                <textarea v-model="plan.description" placeholder="Plan description" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <FormLabel>Plan Pricing</FormLabel>


                        <!--Price-->
                        <div class="block-wrapper">
                            <label>Price:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Plan price" rules="required" v-slot="{ errors }">
                                <input v-model="plan.price" placeholder="Plan price" type="number" step="0.01" min="1" max="99999" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <!--Storage Capacity-->
                        <div class="block-wrapper">
                            <label>Storage Capacity:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Storage capacity" rules="required" v-slot="{ errors }">
                                <input v-model="plan.capacity" placeholder="Storage capacity" type="number" min="1" max="999999999" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                            <small class="input-help">You have to type only number e.g. value '5' means, user will have 5GB of storage capacity.</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit">
                            Create Plan
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
    import {required} from 'vee-validate/dist/rules'
    import { mapGetters } from 'vuex'
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
        },
        data() {
            return {
                isLoading: false,
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

                        // End loading
                        this.isLoading = false

                        // Show toaster
                        events.$emit('toaster', {
                            type: 'success',
                            message: 'Your plan was successfully created!',
                        })

                        // Go to User page
                        this.$router.push({name: 'PlanSettings', params: {id: response.data.data.id}})
                    })
                    .catch(error => {

                        // Validation errors
                        if (error.response.status == 422) {

                            // Email validation error
                            if (error.response.data.errors['email']) {

                                this.$refs.createPlan.setErrors({
                                    'email': error.response.data.errors['email']
                                });
                            }

                            // Password validation error
                            if (error.response.data.errors['password']) {

                                this.$refs.createPlan.setErrors({
                                    'password': error.response.data.errors['password']
                                });
                            }

                            // Password validation error
                            if (error.response.data.errors['storage_capacity']) {

                                this.$refs.createPlan.setErrors({
                                    'storage capacity': 'The storage capacity must be lower than 10 digit number.'
                                });
                            }
                        } else {

                            events.$emit('alert:open', {
                                title: this.$t('popup_error.title'),
                                message: this.$t('popup_error.message'),
                            })
                        }

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
