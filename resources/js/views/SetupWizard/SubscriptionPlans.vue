<template>
    <AuthContentWrapper ref="auth">

        <!--Database Credentials-->
        <AuthContent name="database-credentials" :visible="true">

			<Headline
				class="container mx-auto max-w-screen-sm"
				title="Setup Wizard"
				description="Set up plans for your customers."
			>
                <settings-icon size="40" class="title-icon text-theme mx-auto" />
			</Headline>

            <ValidationObserver @submit.prevent="subscriptionPlansSubmit" ref="subscriptionPlans" v-slot="{ invalid }"
                                tag="form" class="form block-form">
                <FormLabel>Create your plans</FormLabel>
                <InfoBox>
                    <p>Your plans will be <b>sorted automatically</b> in ascent order by plan price. All plans is automatically created as monthly plans.</p>
                </InfoBox>

                <div class="duplicator">
                    <div class="plan-item duplicator-item" v-for="(plan, index) in subscriptionPlans" :key="index++">
                        <x-icon @click="removeRow(plan)" v-if="index !== 1" size="22" class="delete-item"></x-icon>
                        <b class="duplicator-title">{{ index }}. Plan</b>
                        <div class="block-wrapper">
                            <label>Name:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Name"
                                                rules="required" v-slot="{ errors }">
                                <input v-model="plan.attributes.name" placeholder="Type your plan name"
                                       type="text" :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Description (optional):</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Description"
                                                v-slot="{ errors }">
                                <textarea v-model="plan.attributes.description"
                                          placeholder="Type your plan description" :class="{'is-error': errors[0]}"></textarea>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Price:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Price"
                                                rules="required" v-slot="{ errors }">
                                <input v-model="plan.attributes.price" placeholder="Type your plan price" type="number"
                                       step="0.01" min="1" max="999999999999"
                                       :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="block-wrapper">
                            <label>Storage Capacity:</label>
                            <ValidationProvider tag="div" mode="passive" class="input-wrapper" name="Storage Capacity"
                                                rules="required" v-slot="{ errors }">
                                <input v-model="plan.attributes.capacity"
                                       min="1"
                                       max="999999999"
                                       placeholder="Type storage capacity in GB"
                                       type="number"
                                       :class="{'is-error': errors[0]}"/>
                                <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                    </div>

                    <ButtonBase
                            @click.native="addRow"
                            class="duplicator-add-button"
                            button-style="theme-solid"
                    >Add New Plan
                    </ButtonBase>
                </div>

                <InfoBox v-if="isError" type="error" style="margin-top: 40px">
                    <p>{{ errorMessage }}</p>
                </InfoBox>

                <div class="submit-wrapper">
                    <AuthButton icon="chevron-right" :text="submitButtonText" :loading="isLoading"
                                :disabled="isLoading"/>
                </div>
            </ValidationObserver>
        </AuthContent>
    </AuthContentWrapper>
</template>

<script>
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import AuthContentWrapper from '/resources/js/components/Auth/AuthContentWrapper'
    import SelectInput from '/resources/js/components/Others/Forms/SelectInput'
    import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import AuthContent from '/resources/js/components/Auth/AuthContent'
    import AuthButton from '/resources/js/components/Auth/AuthButton'
    import {SettingsIcon} from 'vue-feather-icons'
    import {required} from 'vee-validate/dist/rules'
    import {XIcon} from 'vue-feather-icons'
	import Headline from "../Auth/Headline"
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'subscriptionPlans',
        components: {
            AuthContentWrapper,
            ValidationProvider,
            ValidationObserver,
            SettingsIcon,
            SelectInput,
            AuthContent,
            ButtonBase,
            AuthButton,
            FormLabel,
            required,
            InfoBox,
            XIcon,
			Headline,
        },
        computed: {
            submitButtonText() {
                return this.isLoading ? 'Creating Subscription Stripe Plans' : 'Save and Go Next'
            }
        },
        data() {
            return {
                isLoading: false,
                isError: false,
                errorMessage: '',
                subscriptionPlans: [
                    {
                        id: 1,
                        type: 'plan',
                        attributes: {
                            name: '',
                            description: '',
                            price: '',
                            capacity: '',
                        }
                    }
                ]
            }
        },
        methods: {
            async subscriptionPlansSubmit() {

                // Validate fields
                const isValid = await this.$refs.subscriptionPlans.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true
                this.isError = false

                // Send request to get verify account
                axios
                    .post('/api/setup/stripe-plans', {
                        plans: this.subscriptionPlans
                    })
                    .then(() => {

                        // Redirect to next step
                        this.$router.push({name: 'EnvironmentSetup'})
                    })
                    .catch(error => {

                        if (error.response.status = 500) {
                            this.isError = true
                            this.errorMessage = error.response.data.message
                        }

                    })
                    .finally(() => {
                        this.isLoading = false
                    })
            },
            addRow() {
                this.subscriptionPlans.push({
                    id: Math.floor(Math.random() * 10000000),
                    type: 'plans',
                    attributes: {
                        name: '',
                        description: '',
                        price: '',
                        capacity: '',
                    }
                })
            },
            removeRow(plan) {
                this.subscriptionPlans = this.subscriptionPlans.filter(item => item.id !== plan.id)
            },
        },
        created() {
            this.$scrollTop()
        }
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_forms';
    @import '/resources/sass/vuefilemanager/_auth';
    @import '/resources/sass/vuefilemanager/_setup_wizard';
</style>
