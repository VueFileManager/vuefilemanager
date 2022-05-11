<template>
    <ValidationObserver @submit.prevent="createPlan" ref="createPlan" v-slot="{ invalid }" tag="form">
        <div class="card shadow-card">
            <FormLabel>
                {{ $t('details') }}
            </FormLabel>

            <!--Name-->
            <ValidationProvider tag="div" mode="passive" name="Name" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('name')">
                    <input
                        v-model="plan.name"
                        :placeholder="$t('plan_name')"
                        type="text"
                        :class="{ '!border-rose-600': errors[0] }"
                        class="focus-border-theme input-dark"
                    />
                </AppInputText>
            </ValidationProvider>

            <!--Description-->
            <ValidationProvider tag="div" mode="passive" name="Description" v-slot="{ errors }">
                <AppInputText :title="$t('description_optional')" :is-last="true">
                    <textarea
                        v-model="plan.description"
                        :placeholder="$t('plan_description')"
                        :class="{ '!border-rose-600': errors[0] }"
                        class="focus-border-theme input-dark"
                        maxlength="120"
                    ></textarea>
                </AppInputText>
            </ValidationProvider>
        </div>

        <div class="card shadow-card">
            <FormLabel>
                {{ $t('pricing') }}
            </FormLabel>

            <div class="justify-items md:flex md:space-x-4">
                <!--Price-->
                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="Price"
                    rules="required"
                    v-slot="{ errors }"
                    class="w-full"
                >
                    <AppInputText :title="$t('plan_price')" class="w-full">
                        <input
                            v-model="plan.amount"
                            :placeholder="$t('plan_price')"
                            type="number"
                            step="0.01"
                            min="1"
                            max="999999999999"
                            :class="{ '!border-rose-600': errors[0] }"
                            class="focus-border-theme input-dark"
                        />
                    </AppInputText>
                </ValidationProvider>

                <!--Currency-->
                <ValidationProvider
                    tag="div"
                    mode="passive"
                    name="Currency"
                    rules="required"
                    v-slot="{ errors }"
                    class="w-full"
                >
                    <AppInputText :title="$t('currency')" class="w-full">
                        <SelectInput
                            v-model="plan.currency"
                            :options="currencyList"
                            :placeholder="$t('select_plan_currency')"
                            :isError="errors[0]"
                        />
                    </AppInputText>
                </ValidationProvider>
            </div>

            <!--Interval-->
            <ValidationProvider tag="div" mode="passive" name="Interval" rules="required" v-slot="{ errors }">
                <AppInputText :title="$t('interval')" :is-last="true">
                    <SelectInput
                        v-model="plan.interval"
                        :options="intervalList"
                        :placeholder="$t('select_billing_interval')"
                        :isError="errors[0]"
                    />
                </AppInputText>
            </ValidationProvider>
        </div>

        <div class="card shadow-card">
            <FormLabel>
                {{ $t('features') }}
            </FormLabel>

            <!--Storage Capacity-->
            <ValidationProvider
                tag="div"
                mode="passive"
                name="Max Storage Capacity"
                rules="required"
                v-slot="{ errors }"
            >
                <AppInputText
                    :title="$t('admin_page_plans.form.storage')"
                    :description="$t('admin_page_plans.form.storage_helper')"
                >
                    <input
                        v-model="plan.features.max_storage_amount"
                        :placeholder="$t('admin_page_plans.form.storage_plac')"
                        type="number"
                        min="1"
                        max="999999999"
                        :class="{ '!border-rose-600': errors[0] }"
                        class="focus-border-theme input-dark"
                    />
                </AppInputText>
            </ValidationProvider>

            <!--Team Members-->
            <ValidationProvider tag="div" mode="passive" name="Max Team Members" rules="required" v-slot="{ errors }">
                <AppInputText
                    :title="$t('team_members')"
                    :description="$t('zero_for_unlimited_members')"
                    :is-last="true"
                >
                    <input
                        v-model="plan.features.max_team_members"
                        :placeholder="$t('add_max_team_members')"
                        type="number"
                        min="-1"
                        max="999999999"
                        :class="{ '!border-rose-600': errors[0] }"
                        class="focus-border-theme input-dark"
                    />
                </AppInputText>
            </ValidationProvider>
        </div>

        <InfoBox v-if="isError" type="error" style="margin-top: 40px">
            <p>{{ errorMessage }}</p>
        </InfoBox>

        <ButtonBase :disabled="isLoading" :loading="isLoading" button-style="theme" type="submit" class="w-full sm:w-auto">
            {{ $t('create_plan') }}
        </ButtonBase>
    </ValidationObserver>
</template>

<script>
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import SelectInput from '../../../../components/Inputs/SelectInput'
import ImageInput from '../../../../components/Inputs/ImageInput'
import MobileHeader from '../../../../components/Mobile/MobileHeader'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import SectionTitle from '../../../../components/UI/Labels/SectionTitle'
import ButtonBase from '../../../../components/UI/Buttons/ButtonBase'
import InfoBox from '../../../../components/UI/Others/InfoBox'
import { required } from 'vee-validate/dist/rules'
import { mapGetters } from 'vuex'
import { events } from '../../../../bus'
import axios from 'axios'

export default {
    name: 'CreateFixedPlan',
    components: {
        ValidationProvider,
        ValidationObserver,
        SectionTitle,
        AppInputText,
        MobileHeader,
        SelectInput,
        ButtonBase,
        ImageInput,
        FormLabel,
        required,
        InfoBox,
    },
    computed: {
        ...mapGetters(['currencyList', 'intervalList', 'config']),
    },
    data() {
        return {
            errorMessage: undefined,
            isLoading: false,
            isError: false,
            plan: {
                type: 'fixed',
                name: undefined,
                description: undefined,
                interval: undefined,
                amount: undefined,
                currency: undefined,
                features: {
                    max_storage_amount: undefined,
                    max_team_members: undefined,
                },
            },
        }
    },
    methods: {
        async createPlan() {
            // Validate fields
            const isValid = await this.$refs.createPlan.validate()

            if (!isValid) return

            // Start loading
            this.isLoading = true

            axios
                .post('/api/subscriptions/admin/plans', this.plan)
                .then((response) => {
                    // Show toaster
                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('toaster.plan_created'),
                    })

                    // Go to plan page
                    this.$router.push({
                        name: 'PlanFixedSettings',
                        params: { id: response.data.data.id },
                    })

                    // Set default state {isEmptyPlans} to false
                    if (this.config.isEmptyPlans) {
                        this.$store.commit('REPLACE_CONFIG_VALUE', {
                            key: 'isEmptyPlans',
                            value: false,
                        })
                    }
                })
                .catch((error) => {
                    // Validation errors
                    if (error.response.status === 422) {
                        if (error.response.data.errors['max_storage_amount']) {
                            this.$refs.createPlan.setErrors({
                                'Max Storage Capacity': this.$t('errors.capacity_digit'),
                            })
                        }
                    }

					if (error.response.status === 500 && error.response.data.type) {
						events.$emit('alert:open', {
							title: error.response.data.title,
							message: error.response.data.message,
						})
					} else if (error.response.status === 500) {
                        this.isError = true
                        this.errorMessage = error.response.data.message
                    }
                })
                .finally(() => {
                    this.isLoading = false
                })
        },
    },
}
</script>
