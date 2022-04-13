<template>
    <PageTab :is-loading="isLoading" v-if="storage">
        <!--Storage Usage-->
        <div v-if="distribution" class="card shadow-card">
            <FormLabel icon="hard-drive">
                {{ $t('storage_usage') }}
            </FormLabel>

            <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                {{ storage.data.attributes.used }}
            </b>

            <b
                v-if="
                    config.subscriptionType === 'fixed' || (config.subscriptionType === 'none' && config.storageLimit)
                "
                class="mt-0.5 block text-sm dark:text-gray-500 text-gray-400"
            >
                {{ $t('total_of', {capacity: storage.data.attributes.capacity}) }}
                {{ $t('used') }}
            </b>

            <ProgressLine v-if="storage.data.attributes.used !== '0B'" :data="distribution" class="mt-5" />
        </div>

        <!--Upload-->
        <div v-if="distribution" class="card shadow-card">
            <FormLabel icon="hard-drive">
                {{ $t('upload') }}
            </FormLabel>

            <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                {{ storage.data.meta.traffic.upload }}
            </b>

            <b class="mb-3 mb-5 block text-sm dark:text-gray-500 text-gray-400">
                {{ $t('in_last_x_days') }}
            </b>

            <BarChart :data="storage.data.meta.traffic.chart.upload" color="#FFBD2D" />
        </div>

        <!--Download-->
        <div v-if="distribution" class="card shadow-card">
            <FormLabel icon="hard-drive">
                {{ $t('download') }}
            </FormLabel>

            <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                {{ storage.data.meta.traffic.download }}
            </b>

            <b class="mb-3 mb-5 block text-sm dark:text-gray-500 text-gray-400">
                {{ $t('in_last_x_days') }}
            </b>

            <BarChart :data="storage.data.meta.traffic.chart.download" color="#9d66fe" />
        </div>

        <!--Set Storage Size-->
        <div
            v-if="config.storageLimit && !user.data.attributes.subscription && config.subscriptionType !== 'metered'"
            class="card shadow-card"
        >
            <FormLabel>
                {{ $t('user_box_storage.title') }}
            </FormLabel>
            <ValidationObserver
                ref="changeStorageCapacity"
                @submit.prevent="changeStorageCapacity"
                v-slot="{ invalid }"
                tag="form"
            >
                <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Capacity" rules="required">
                    <AppInputText
                        :title="$t('admin_page_user.label_change_capacity')"
                        :description="$t('user_box_storage.description')"
                        :error="errors[0]"
                        :is-last="true"
                    >
                        <div class="space-y-4 sm:flex sm:space-x-4 sm:space-y-0">
                            <input
                                v-model="capacity"
                                :placeholder="$t('admin_page_user.label_change_capacity')"
                                type="number"
                                min="1"
                                max="999999999"
                                class="focus-border-theme input-dark"
                                :class="{ '!border-rose-600': errors[0] }"
                            />
                            <ButtonBase
                                :loading="isSendingRequest"
                                :disabled="isSendingRequest"
                                type="submit"
                                button-style="theme"
                                class="w-full sm:w-auto"
                            >
                                {{ $t('change_capacity') }}
                            </ButtonBase>
                        </div>
                    </AppInputText>
                </ValidationProvider>
            </ValidationObserver>
        </div>
    </PageTab>
</template>

<script>
import ProgressLine from '../../../../components/UI/ProgressChart/ProgressLine'
import AppInputText from '../../../../components/Forms/Layouts/AppInputText'
import FormLabel from '../../../../components/UI/Labels/FormLabel'
import InfoBox from '../../../../components/UI/Others/InfoBox'
import PageTabGroup from '../../../../components/Layout/PageTabGroup'
import PageTab from '../../../../components/Layout/PageTab'
import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full'
import ButtonBase from '../../../../components/UI/Buttons/ButtonBase'
import { required } from 'vee-validate/dist/rules'
import BarChart from '../../../../components/UI/BarChart/BarChart'
import { events } from '../../../../bus'
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
    name: 'UserStorage',
    props: ['user'],
    components: {
        ProgressLine,
        AppInputText,
        PageTabGroup,
        FormLabel,
        PageTab,
        InfoBox,
        ValidationProvider,
        ValidationObserver,
        ButtonBase,
        required,
        BarChart,
    },
    computed: {
        ...mapGetters(['config']),
    },
    data() {
        return {
            isLoading: true,
            isSendingRequest: false,
            capacity: undefined,
            storage: undefined,
            distribution: undefined,
        }
    },
    methods: {
        async changeStorageCapacity() {
            // Validate fields
            const isValid = await this.$refs.changeStorageCapacity.validate()

            if (!isValid) return

            this.isSendingRequest = true

            // Send request to get user reset link
            axios
                .post(this.$store.getters.api + '/admin/users/' + this.$route.params.id + '/capacity', {
                    attributes: {
                        max_storage_amount: this.capacity,
                    },
                    _method: 'patch',
                })
                .then(() => {
                    // Reset errors
                    this.$refs.changeStorageCapacity.reset()

                    this.isSendingRequest = false

                    this.getStorageDetails()

                    events.$emit('toaster', {
                        type: 'success',
                        message: this.$t('toaster.changed_capacity'),
                    })
                })
                .catch((error) => {
                    this.isSendingRequest = false

                    if (error.response.status == 422) {
                        // Password validation error
                        if (error.response.data.errors['attributes.max_storage_amount']) {
                            this.$refs.changeStorageCapacity.setErrors({
                                Capacity: this.$t('errors.capacity_digit'),
                            })
                        }
                    } else {
                        events.$emit('alert:open', {
                            title: this.$t('popup_error.title'),
                            message: this.$t('popup_error.message'),
                        })
                    }
                })
        },
        getStorageDetails() {
            axios.get('/api/admin/users/' + this.$route.params.id + '/storage').then((response) => {
                this.distribution = this.$mapStorageUsage(response.data)

                this.storage = response.data

                this.isLoading = false
            })
        },
    },
    created() {
        this.getStorageDetails()
    },
}
</script>
