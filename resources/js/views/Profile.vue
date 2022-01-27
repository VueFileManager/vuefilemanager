<template>
    <div class="lg:flex md:h-screen md:overflow-hidden dark:bg-dark-background bg-light-background">
		<!--On Top of App Components-->
        <FilePreview />
		<Spotlight />

		<ConfirmPopup />

		<ConfirmPassword />

		<!--2FA popups-->
		<TwoFactorRecoveryCodesPopup />
		<TwoFactorQrSetupPopup />

		<!--Access Token Popup-->
		<CreatePersonalTokenPopup />

		<!--Payments Popup-->
		<SelectPlanSubscriptionPopup />
		<SelectSingleChargeMethodPopup />

		<SidebarNavigation />

		<!--Navigations-->
        <MobileNavigation />

		<MobileNavigationToolbar />

        <div v-if="user" class="lg:pt-6 md:px-6 px-2.5 lg:pb-0 pb-12 w-full overflow-x-hidden relative xl:max-w-screen-lg md:max-w-4xl mx-auto">
            <div v-if="! isLoading" id="page-content">

				<div class="card shadow-card sticky top-0 z-10" style="padding-bottom: 0">

					<!--User thumbnail-->
					<div class="flex items-center mb-3">

						<!--Image input for replace avatar-->
						<AvatarInput v-model="avatar" :avatar="user.data.relationships.settings.data.attributes.avatar.md" />

						<!--User name & email-->
						<div class="ml-4">
							<b class="sm:text-lg text-md font-bold block">
								{{ user.data.relationships.settings.data.attributes.first_name }} {{ user.data.relationships.settings.data.attributes.last_name }}

								<ColorLabel v-if="config.subscriptionType === 'fixed'" :color="subscriptionColor">
									{{ subscriptionStatus }}
								</ColorLabel>
							</b>
							<small class="sm:text-sm text-xs text-gray-600 block">
								{{ user.data.attributes.email }}
							</small>
						</div>
					</div>

					<CardNavigation :pages="pages" class="-mx-1" />
				</div>

				<!--Router Content-->
				<router-view :user="user" />
            </div>
            <div id="loader" v-if="isLoading">
                <Spinner></Spinner>
            </div>
        </div>
    </div>
</template>

<script>
	import MobileNavigation from "../components/Others/MobileNavigation";
	import SelectSingleChargeMethodPopup from "../components/Others/SelectSingleChargeMethodPopup";
	import SelectPlanSubscriptionPopup from "../components/Subscription/SelectPlanSubscriptionPopup";
	import ConfirmPopup from "../components/Others/Popup/ConfirmPopup";
	import FilePreview from '/resources/js/components/FilePreview/FilePreview'
	import Spotlight from '/resources/js/components/Spotlight/Spotlight'
	import TwoFactorRecoveryCodesPopup from '/resources/js/components/Others/TwoFactorRecoveryCodesPopup'
	import CreatePersonalTokenPopup from '/resources/js/components/Others/CreatePersonalTokenPopup'
	import TwoFactorQrSetupPopup from '/resources/js/components/Others/TwoFactorQrSetupPopup'
	import AvatarInput from '/resources/js/components/Others/Forms/AvatarInput'
	import SidebarNavigation from "../components/Sidebar/SidebarNavigation"
	import ColorLabel from '/resources/js/components/Others/ColorLabel'
	import Spinner from '/resources/js/components/FilesView/Spinner'
	import {mapGetters} from 'vuex'
	import CardNavigation from "../components/Admin/CardNavigation";
	import ConfirmPassword from "../components/Others/ConfirmPassword";
	import MobileNavigationToolbar from "./MobileNavigationToolbar";

	export default {
        name: 'Settings',
        components: {
			MobileNavigationToolbar,
			MobileNavigation,
			ConfirmPassword,
			SelectSingleChargeMethodPopup,
			SelectPlanSubscriptionPopup,
			ConfirmPopup,
			CardNavigation,
			FilePreview,
			Spotlight,
			TwoFactorRecoveryCodesPopup,
			CreatePersonalTokenPopup,
			TwoFactorQrSetupPopup,
			SidebarNavigation,
            AvatarInput,
            ColorLabel,
            Spinner,

        },
        computed: {
            ...mapGetters([
				'config',
            	'user',
			]),
            subscriptionStatus() {
                return this.user.data.relationships.subscription
					? this.$t('global.premium')
					: this.$t('global.free')
            },
            subscriptionColor() {
                return this.user.data.relationships.subscription
					? 'green'
					: 'purple'
            },
            canShowUpgradeWarning() {
                return this.config.storageLimit && this.user.data.attributes.storage.used > 95
            },
            canShowIncompletePayment() {
                return this.user.data.attributes.incomplete_payment
            },
			pages() {
				let list = [
					{
						title: this.$t('menu.profile'),
						route: 'Profile',
					},
					{
						title: this.$t('menu.password'),
						route: 'Password',
					},
					{
						title: this.$t('menu.storage'),
						route: 'Storage',
					},
				]

				// Push billing item if subscription is set
				if (['fixed', 'metered'].includes(this.config.subscriptionType)) {
					list.push({
						title: this.$t('Billing'),
						route: 'Billing',
					})
				}

				return list
			}
        },
        data() {
            return {
				avatar: undefined,
                isLoading: false,
            }
        }
	}
</script>
