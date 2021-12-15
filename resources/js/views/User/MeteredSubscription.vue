<template>
    <PageTab :is-loading="isLoading">

		<!--Balance-->
		<div class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Balance') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ user.data.relationships.balance.data.attributes.formatted }}
			</b>

			<ValidationObserver ref="changeStorageCapacity" @submit.prevent="makePayment" v-slot="{ invalid }" tag="form" class="mt-6">
                <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Capacity" rules="required">
					<AppInputText :description="$t('The amount will be increased as soon as we register your charge from payment gateway.')" :error="errors[0]" :is-last="true">
						<div class="flex space-x-4">
							<input v-model="paymentAmount"
								   :placeholder="$t('Fund Your Account Balance...')"
								   type="number"
								   min="1"
								   max="999999999"
								   class="focus-border-theme input-dark"
								   :class="{'is-error': errors[0]}"
							/>
							<ButtonBase type="submit" button-style="theme" class="submit-button">
								{{ $t('Make a Payment') }}
							</ButtonBase>
						</div>
					</AppInputText>
                </ValidationProvider>
            </ValidationObserver>
		</div>

		<!--Usage Estimates-->
		<div class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Usage Estimates') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				$22.93
			</b>

			<b class="mb-3 block text-sm text-gray-400 mb-5">
				From 2. December till now
			</b>

			<div>
				<div class="flex items-center justify-between py-2 border-b dark:border-opacity-5 border-light border-dashed" v-for="(usage, i) in usages" :ke="i">
					<div class="w-2/4 leading-none">
						<b class="text-sm font-bold leading-none">
							{{ usage.title }}
						</b>
						<small class="text-xs text-gray-500 pt-2 leading-none block">
							{{ usage.description }}
						</small>
					</div>
					<div class="text-left w-1/4">
						<span class="text-sm font-bold text-gray-560">
							{{ usage.usage }}
						</span>
					</div>
					<div class="text-right w-1/4">
						<span class="text-sm font-bold text-theme">
							{{ usage.amount }}
						</span>
					</div>
				</div>
			</div>
		</div>

		<!--Billing Alert-->
		<div class="card shadow-card">
			<FormLabel icon="bell">
                {{ $t('Billing Alert') }}
            </FormLabel>

			<ValidationObserver ref="changeStorageCapacity" @submit.prevent="makePayment" v-slot="{ invalid }" tag="form" class="mt-6">
                <ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Capacity" rules="required">
					<AppInputText :description="$t('You will receive an email whenever your monthly balance reaches the specified amount above.')" :error="errors[0]" :is-last="true">
						<div class="flex space-x-4">
							<input v-model="paymentAmount"
								   :placeholder="$t('Alert Amount...')"
								   type="number"
								   min="1"
								   max="999999999"
								   class="focus-border-theme input-dark"
								   :class="{'is-error': errors[0]}"
							/>
							<ButtonBase type="submit" button-style="theme" class="submit-button">
								{{ $t('Set Alert') }}
							</ButtonBase>
						</div>
					</AppInputText>
                </ValidationProvider>
            </ValidationObserver>
		</div>

		<!--Transactions-->
		<div class="card shadow-card">
			<FormLabel icon="file-text">
                {{ $t('Transactions') }}
            </FormLabel>

			<DatatableWrapper
				@init="isLoading = false"
				api="/api/subscriptions/transactions"
				:paginator="true"
				:columns="columns"
			>
                <template slot-scope="{ row }">
                    <tr class="border-b dark:border-opacity-5 border-light border-dashed">
                        <td class="py-4">
                            <span class="text-sm font-bold">
								{{ row.data.attributes.note }}
							</span>
                        </td>
                        <td>
							<ColorLabel :color="$getTransactionStatusColor(row.data.attributes.status)">
                                {{ row.data.attributes.status }}
							</ColorLabel>
                        </td>
                        <td>
                            <span class="text-sm font-bold" :class="$getTransactionTypeTextColor(row.data.attributes.type)">
                                {{ $getTransactionMark(row.data.attributes.type) + row.data.attributes.price }}
                            </span>
                        </td>
                        <td>
                            <span class="text-sm font-bold">
                                {{ row.data.attributes.created_at }}
                            </span>
                        </td>
                        <td class="text-right">
                            <img class="inline-block max-h-5" :src="$getPaymentLogo(row.data.attributes.driver)" :alt="row.data.attributes.driver">
                        </td>
                    </tr>
                </template>

				<!--Empty page-->
                <template v-slot:empty-page>
                    <InfoBox>
                        <p>{{ $t('admin_page_user.invoices.empty') }}</p>
                    </InfoBox>
                </template>
            </DatatableWrapper>
		</div>
    </PageTab>
</template>

<script>
	import ColorLabel from "../../components/Others/ColorLabel";
	import DatatableWrapper from "../../components/Others/Tables/DatatableWrapper";
	import AppInputText from "../../components/Admin/AppInputText";
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
	import AppInputSwitch from "../../components/Admin/AppInputSwitch"
	import FormLabel from "../../components/Others/Forms/FormLabel"
	import ProgressLine from "../../components/Admin/ProgressLine"
	import {mapGetters} from 'vuex'
	import axios from 'axios'

	export default {
		name: 'MeteredSubscription',
		components: {
			DatatableWrapper,
			ValidationProvider,
			ValidationObserver,
			AppInputSwitch,
			AppInputText,
			ProgressLine,
			ButtonBase,
			ColorLabel,
			FormLabel,
			InfoBox,
			PageTab,
		},
		computed: {
			...mapGetters([
				'user',
			]),
		},
		data() {
			return {
				isLoading: false,
				paymentAmount: undefined,
				usages: [
					{
						title: 'Storage',
						description: this.$t('Total storage amount you are using.'),
						usage: '12.23GB',
						amount: '$4.98',
					},
					{
						title: 'Bandwidth',
						description: this.$t('Data amount you transferred to/from your account.'),
						usage: '3.61GB',
						amount: '$2.89',
					},
					{
						title: 'Members',
						description: this.$t('Total members you invited to your team folders.'),
						usage: '12',
						amount: '$1.20',
					},
					{
						title: 'Platform Fee',
						usage: '1',
						amount: '$2.00',
					},
				],
				columns: [
					{
						label: this.$t('Note'),
						field: 'note',
						sortable: true
					},
					{
						label: this.$t('Status'),
						field: 'status',
						sortable: true
					},
					{
						label: this.$t('admin_page_invoices.table.total'),
						field: 'amount',
						sortable: true
					},
					{
						label: this.$t('Payed At'),
						field: 'created_at',
						sortable: true
					},
					{
						label: this.$t('Service'),
						field: 'driver',
						sortable: true
					},
				],
			}
		},
		methods: {
			makePayment() {
				// TODO: make a payment
			}
		},
		created() {

		}
	}
</script>
