<template>
    <PageTab :is-loading="isLoading" v-if="storage">

		<div v-if="distribution" class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Storage Usage') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ storage.data.attributes.used }}
			</b>

			<b class="mb-3 block text-sm text-gray-400 mb-5">
				{{ $t('Total of') }} {{ storage.data.attributes.capacity }} {{ $t('Used') }}
			</b>

			<ProgressLine :data="distribution" />
		</div>
		<div v-if="distribution" class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Upload') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ storage.data.meta.traffic.upload }}
			</b>

			<b class="mb-3 block text-sm text-gray-400 mb-5">
				{{ $t('In last 45 days') }}
			</b>

			<BarChart :data="storage.data.meta.traffic.chart.upload" color="#FFBD2D" />
		</div>
		<div v-if="distribution" class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Download') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ storage.data.meta.traffic.download }}
			</b>

			<b class="mb-3 block text-sm text-gray-400 mb-5">
				{{ $t('In last 45 days') }}
			</b>

			<BarChart :data="storage.data.meta.traffic.chart.download" color="#9d66fe" />
		</div>

        <div v-if="config.storageLimit && ! user.data.attributes.subscription" class="card shadow-card">
            <FormLabel>
                {{ $t('user_box_storage.title') }}
            </FormLabel>
            <InfoBox>
                <p>{{ $t('user_box_storage.description') }}</p>
            </InfoBox>
            <ValidationObserver ref="changeStorageCapacity" @submit.prevent="changeStorageCapacity" v-slot="{ invalid }" tag="form">
                <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Capacity" rules="required">
					<AppInputText :title="$t('admin_page_user.label_change_capacity')" :error="errors[0]" :is-last="true">
						<div class="flex space-x-4">
							<input v-model="capacity"
								   :placeholder="$t('admin_page_user.label_change_capacity')"
								   type="number"
								   min="1"
								   max="999999999"
								   class="focus-border-theme input-dark"
								   :class="{'is-error': errors[0]}"
							/>
							<ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit" button-style="theme" class="submit-button">
								{{ $t('admin_page_user.change_capacity') }}
							</ButtonBase>
						</div>
					</AppInputText>
                </ValidationProvider>
            </ValidationObserver>
        </div>
    </PageTab>
</template>

<script>
	import ProgressLine from "../../../../components/Admin/ProgressLine";
	import AppInputText from "../../../../components/Admin/AppInputText";
    import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import PageTabGroup from '/resources/js/components/Others/Layout/PageTabGroup'
    import PageTab from '/resources/js/components/Others/Layout/PageTab'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import SetupBox from '/resources/js/components/Others/Forms/SetupBox'
    import {required} from 'vee-validate/dist/rules'
	import BarChart from "../../../User/BarChart"
    import {events} from '/resources/js/bus'
    import {mapGetters} from "vuex"
    import axios from 'axios'

    export default {
        name: 'UserStorage',
        props: [
			'user'
		],
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
            SetupBox,
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
                const isValid = await this.$refs.changeStorageCapacity.validate();

                if (!isValid) return;

                this.isSendingRequest = true

                // Send request to get user reset link
                axios
                    .post(this.$store.getters.api + '/admin/users/' + this.$route.params.id + '/capacity', {
                        attributes: {
                            max_storage_amount: this.capacity
                        },
                        _method: 'patch'
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
                    .catch(error => {

                        this.isSendingRequest = false

                        if (error.response.status == 422) {

                            // Password validation error
                            if (error.response.data.errors['attributes.max_storage_amount']) {

                                this.$refs.changeStorageCapacity.setErrors({
                                    'Capacity': this.$t('errors.capacity_digit')
                                });
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
                axios.get('/api/admin/users/' + this.$route.params.id + '/storage')
                    .then(response => {
						this.distribution = this.$mapStorageUsage(response.data)

						this.storage = response.data

                        this.isLoading = false
                    })
            }
        },
        created() {
            this.getStorageDetails()
        }
    }
</script>
