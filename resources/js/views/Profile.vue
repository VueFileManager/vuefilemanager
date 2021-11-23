<template>
    <div class="sm:flex md:h-screen md:overflow-hidden" style="background: rgba(244, 245, 246, 0.6)">
		<!--On Top of App Components-->
        <FilePreview />
		<Spotlight />

		<!--2FA popups-->
		<TwoFactorRecoveryCodesPopup />
		<TwoFactorSetupPopup />

		<!--Access Token Popup-->
		<CreatePersonaTokenPopup />

		<SidebarNavigation />

        <div v-if="user" class="px-6 w-full overflow-x-hidden relative pt-6 xl:max-w-screen-lg md:max-w-screen-md mx-auto">
            <div v-if="! isLoading" id="page-content">

				<div class="card shadow-card pb-0 sticky top-0 z-10">
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
                                    <ColorLabel v-if="config.isSaaS" :color="subscriptionColor">
                                        {{ subscriptionStatus }}
                                    </ColorLabel>
                                </b>
                                <span class="email">{{ user.data.attributes.email }}</span>
                            </div>
                        </div>
						<!--<div v-if="config.storageLimit && config.isSaaS && config.app_payments_active && !canShowIncompletePayment" class="headline-actions">
							<ButtonBase @click.native="$openUpgradeOptions" class="upgrade-button" button-style="secondary" type="button">
								{{ $t('global.upgrade_plan') }}
							</ButtonBase>
                        </div>-->
                    </div>


					<CardNavigation :pages="pages" class="-mx-6" />

					<!--Incomplete Payment Warning-->
					<!--<InfoBox v-if="canShowIncompletePayment" type="error" class="message-box">
                        <i18n path="incomplete_payment.description" tag="p">
                            <a :href="user.data.attributes.incomplete_payment">{{ $t('incomplete_payment.href') }}</a>
                        </i18n>
                    </InfoBox>-->

					<!--Upgrade Storage Plan Warning-->
					<!--<InfoBox v-if="canShowUpgradeWarning && !canShowIncompletePayment" type="error" class="message-box">
                        <p>{{ $t('upgrade_banner.title') }}</p>
                    </InfoBox>-->
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

	export default {
        name: 'Settings',
        components: {
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
            	'user',
				'config'
			]),
            subscriptionStatus() {
                return this.user.data.attributes.subscription ? this.$t('global.premium') : this.$t('global.free')
            },
            subscriptionColor() {
                return this.user.data.attributes.subscription ? 'green' : 'purple'
            },
            canShowUpgradeWarning() {
                return this.config.storageLimit && this.user.data.attributes.storage.used > 95
            },
            canShowIncompletePayment() {
                return this.user.data.attributes.incomplete_payment
            }
        },
        data() {
            return {
                avatar: undefined,
                isLoading: false,
				pages: [
					{
						title: this.$t('menu.profile'),
						route: 'Profile',
					},
					{
						title: this.$t('menu.storage'),
						route: 'Storage',
					},
					{
						title: this.$t('menu.password'),
						route: 'Password',
					},
					{
						title: this.$t('menu.subscription'),
						route: 'Subscription',
					},
					{
						title: this.$t('menu.payment_cards'),
						route: 'PaymentMethods',
					},
					{
						title: this.$t('menu.invoices'),
						route: 'Invoice',
					},
				]
            }
        },
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
