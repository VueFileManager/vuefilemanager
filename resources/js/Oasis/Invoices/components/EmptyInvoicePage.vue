<template>
    <div class="empty-page" v-if="isLoading || isEmpty">
        <div class="empty-state">

			<!--Invoice message-->
            <div class="text-content" v-if="hasBillingProfile && $isThisLocation(['regular-invoice', 'advance-invoice']) && !isLoading">
                <h1 class="title">
					{{ $t('in.empty.invoice_page_title') }}
				</h1>

                <p class="description">
					{{ $t('in.empty.invoice_page_description') }}
				</p>

                <ButtonBase @click.native="createInvoice" button-style="theme" class="button">
					{{ buttonTitle }}
				</ButtonBase>
            </div>

			<!--Client Message-->
            <div class="text-content" v-if="hasBillingProfile && $isThisLocation('clients') && !isLoading">
                <h1 class="title">
					{{ $t('in.empty.client_page_title') }}
				</h1>

                <p class="description">
					{{ $t('in.empty.invoice_page_description') }}
				</p>

                <ButtonBase @click.native="createClient" button-style="theme" class="button">
					{{ $t('in.form.create_client') }}
				</ButtonBase>
            </div>

			<!--Billing Profile Message-->
            <div class="text-content" v-if="! hasBillingProfile && ! isLoading">
                <h1 class="title">
					{{ $t('in.empty.bill_profile_title') }}
				</h1>

                <p class="description">
					{{ $t('in.empty.bill_profile_description') }}
				</p>

                <ButtonBase @click.native="createBillingProfile" button-style="theme" class="button">
					{{ $t('in.button.setup_bill_profile') }}
				</ButtonBase>
            </div>

			<!--Spinner-->
            <div class="text-content" v-if="isLoading">
                <Spinner />
            </div>
        </div>
    </div>
</template>

<script>
	import ButtonBase from '@/components/FilesView/ButtonBase'
	import Spinner from '@/components/FilesView/Spinner'
	import {mapGetters} from 'vuex'

	export default {
		name: 'EmptyFilePage',
		props: [
			'title',
			'description',
		],
		components: {
			ButtonBase,
			Spinner,
		},
		computed: {
			...mapGetters([
				'currentFolder',
				'isLoading',
				'entries',
				'user',
			]),
			isEmpty() {
				return this.entries && this.entries.length == 0
			},
			buttonTitle() {
				return this.$isThisLocation('regular-invoice') ? this.$t('in_editor.page.create_regular_invoice') : this.$t('in_editor.page.create_advance_invoice')
			},
			hasBillingProfile() {
				return this.user && this.user.data.attributes.has_billing_profile
			}
		},
		methods: {
			createInvoice() {
				this.$router.push({name: 'CreateInvoice', query: {type: this.currentFolder.location}})
			},
			createClient() {
				this.$router.push({name: 'CreateClient'})
			},
			createBillingProfile() {
				this.$router.push({name: 'BillingProfileSetUp'})
			}
		}
	}
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
	@import '@assets/vuefilemanager/_mixins';

	.empty-page {
		position: absolute;
		left: 0;
		right: 0;
		bottom: 0;
		top: 0;
		margin-top: 20px;
		display: flex;
		align-items: center;

		.empty-state {
			margin: 0 auto;
			padding-left: 15px;
			padding-right: 15px;
		}
	}

	.text-content {
		text-align: center;
		margin: 30px 0;

		.title {
			@include font-size(20);
			color: $text;
			font-weight: 700;
			margin: 0;
		}

		.description {
			@include font-size(13);
			color: $text-muted;
			margin-bottom: 20px;
			display: block;
		}

		.button {
			margin-left: auto;
			margin-right: auto;
		}
	}

	@media (prefers-color-scheme: dark) {
		.text-content {

			.title {
				color: $dark_mode_text_primary;
			}

			.description {
				color: $dark_mode_text_secondary;
			}
		}
	}
</style>
