<template>
    <div class="sm:flex md:h-screen md:overflow-hidden dark:bg-dark-background bg-light-background">
		<!--On Top of App Components-->
        <FilePreview />
		<Spotlight />

		<ConfirmPopup />

		<!--2FA popups-->
		<TwoFactorRecoveryCodesPopup />
		<TwoFactorSetupPopup />

		<!--Access Token Popup-->
		<CreatePersonaTokenPopup />

		<!--Payments Popup-->
		<SelectPlanSubscriptionPopup />
		<SelectSingleChargeMethodPopup />

		<SidebarNavigation />

        <div v-if="user" class="px-6 w-full overflow-x-hidden relative pt-6 xl:max-w-screen-lg md:max-w-screen-md mx-auto">
            <div v-if="! isLoading" id="page-content">

				<div class="card shadow-card sticky top-0 z-10" style="padding-bottom: 0">
					<!--User thumbnail-->
                    <div class="mb-3">
                        <div class="user-thumbnail">
                            <div class="avatar">
                                <UserImageInput
									v-model="avatar"
									:avatar="user.data.relationships.settings.data.attributes.avatar.md"
								/>
                            </div>
                            <div class="info">
                                <b class="name">
                                    {{ user.data.relationships.settings.data.attributes.name }}

                                    <ColorLabel v-if="this.config.subscriptionType === 'fixed'" :color="subscriptionColor">
                                        {{ subscriptionStatus }}
                                    </ColorLabel>
                                </b>
                                <span class="email">{{ user.data.attributes.email }}</span>
                            </div>
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
	import SelectSingleChargeMethodPopup from "../components/Others/SelectSingleChargeMethodPopup";
	import ButtonBase from "../components/FilesView/ButtonBase";
	import SelectPlanSubscriptionPopup from "../components/Subscription/SelectPlanSubscriptionPopup";
	import ConfirmPopup from "../components/Others/Popup/ConfirmPopup";
    import FilePreview from '/resources/js/components/FilePreview/FilePreview'
	import Spotlight from '/resources/js/components/Spotlight/Spotlight'
	import TwoFactorRecoveryCodesPopup from '/resources/js/components/Others/TwoFactorRecoveryCodesPopup'
	import CreatePersonaTokenPopup from '/resources/js/components/Others/CreatePersonaTokenPopup'
	import TwoFactorSetupPopup from '/resources/js/components/Others/TwoFactorSetupPopup'
	import UserImageInput from '/resources/js/components/Others/UserImageInput'
	import SidebarNavigation from "../components/Sidebar/SidebarNavigation"
	import ColorLabel from '/resources/js/components/Others/ColorLabel'
	import Spinner from '/resources/js/components/FilesView/Spinner'
	import {mapGetters} from 'vuex'
	import CardNavigation from "../components/Admin/CardNavigation";
	import {events} from "../bus";

	export default {
        name: 'Settings',
        components: {
			SelectSingleChargeMethodPopup,
			SelectPlanSubscriptionPopup,
			ButtonBase,
			ConfirmPopup,
			CardNavigation,
			FilePreview,
			Spotlight,
			TwoFactorRecoveryCodesPopup,
			CreatePersonaTokenPopup,
			TwoFactorSetupPopup,
			SidebarNavigation,
            UserImageInput,
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
        },
		created() {
			//setTimeout(() => this.$openUpgradeOptions(), 300)
		}
	}
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .user-thumbnail {
        display: flex;
        align-items: center;
        cursor: pointer;

        .avatar {
            margin-right: 20px;

            img {
                line-height: 0;
                width: 62px;
                height: 62px;
                border-radius: 12px;
                z-index: 1;
                position: relative;
            }
        }

        .info {

            .name {
                display: block;
                @include font-size(17);
                line-height: 1;
            }

            .email {
                color: $text-muted;
                @include font-size(14);
            }
        }
    }

    .message-box {
        margin-top: -15px;
    }

    .dark {
        .user-thumbnail {

            .info {

                .name {
                    color: $dark_mode_text_primary;
                }

                .email {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }
</style>
