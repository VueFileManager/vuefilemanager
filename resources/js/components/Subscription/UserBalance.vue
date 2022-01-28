<template>
	<div v-if="! hasPaymentMethod" class="card shadow-card">
		<FormLabel icon="dollar">
			{{ $t('Balance') }}
		</FormLabel>

		<b class="sm:text-3xl text-2xl font-extrabold -mt-3 block mb-0.5">
			{{ user.data.relationships.balance.data.attributes.formatted }}
		</b>

		<!-- Make payment form -->
		<ValidationObserver ref="fundAccount" @submit.prevent="makePayment" v-slot="{ invalid }" tag="form" class="mt-6">
			<ValidationProvider tag="div" v-slot="{ errors }" mode="passive" name="Amount" :rules="`required|min_value:${user.data.meta.totalDebt.amount}`">
				<AppInputText :description="$t('The amount will be increased as soon as we register your charge from payment gateway.')" :error="errors[0]" :is-last="true">
					<div class="sm:flex sm:space-x-4 sm:space-y-0 space-y-4">
						<input v-model="chargeAmount"
							   :placeholder="$t('Fund Your Account Balance...')"
							   type="number"
							   min="1"
							   max="999999999"
							   class="focus-border-theme input-dark"
							   :class="{'border-red': errors[0]}"
						/>
						<ButtonBase type="submit" button-style="theme" class="sm:w-auto w-full">
							{{ $t('Make a Payment') }}
						</ButtonBase>
					</div>
				</AppInputText>
			</ValidationProvider>
		</ValidationObserver>
	</div>
</template>
<script>
import {ValidationObserver, ValidationProvider} from 'vee-validate/dist/vee-validate.full'
import ButtonBase from "../FilesView/ButtonBase";
import FormLabel from "../Others/Forms/FormLabel"
import AppInputText from "../Admin/AppInputText"
import {mapGetters} from "vuex";

export default {
	name: 'UserBalance',
	components: {
		ValidationObserver,
		ValidationProvider,
		AppInputText,
		ButtonBase,
		FormLabel
	},
	computed: {
		...mapGetters([
			'user',
		]),
		hasPaymentMethod() {
			return this.user.data.relationships.creditCards && this.user.data.relationships.creditCards.data.length > 0
		},
	},
	data() {
		return {
			chargeAmount: undefined,
		}
	},
	methods: {
		async makePayment() {
			// Validate fields
			const isValid = await this.$refs.fundAccount.validate();

			if (!isValid) return;

			// Show payment methods popup
			this.$store.dispatch('callSingleChargeProcess', this.chargeAmount)
		},
	}
}
</script>