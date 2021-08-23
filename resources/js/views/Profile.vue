<template>
    <section id="viewport">
		<!--On Top of App Components-->
        <FilePreview />
		<Spotlight />

		<SidebarNavigation />
        <PanelNavigationUser />

        <div v-if="user" id="single-page">
            <div v-if="! isLoading" id="page-content" class="medium-width">
                <MobileHeader :title="$t($router.currentRoute.meta.title)"/>

                <div class="content-page">

                    <!--User thumbnail-->
                    <div class="page-detail-headline">
                        <div class="user-thumbnail">
                            <div class="avatar">
                                <UserImageInput
                                        v-model="avatar"
                                        :avatar="user.data.relationships.settings.data.attributes.avatar"
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
                        <div v-if="config.storageLimit && config.isSaaS && config.app_payments_active && !canShowIncompletePayment" class="headline-actions">
                            <router-link :to="{name: 'UpgradePlan'}">
                                <ButtonBase class="upgrade-button" button-style="secondary" type="button">
                                    {{ $t('global.upgrade_plan') }}
                                </ButtonBase>
                            </router-link>
                        </div>
                    </div>

                    <!--Incomplete Payment Warning-->
                    <InfoBox v-if="canShowIncompletePayment" type="error" class="message-box">
                        <i18n path="incomplete_payment.description" tag="p">
                            <a :href="user.data.attributes.incomplete_payment">{{ $t('incomplete_payment.href') }}</a>
                        </i18n>
                    </InfoBox>

                    <!--Upgrade Storage Plan Warning-->
                    <InfoBox v-if="canShowUpgradeWarning && !canShowIncompletePayment" type="error" class="message-box">
                        <p>{{ $t('upgrade_banner.title') }}</p>
                    </InfoBox>

                    <!--Router Content-->
                    <router-view :user="user" />
                </div>
            </div>
            <div id="loader" v-if="isLoading">
                <Spinner></Spinner>
            </div>
        </div>

		<!--2FA popups-->
		<TwoFactorRecoveryCodesPopup />
		<TwoFactorSetupPopup />

		<!--Access Token Popup-->
		<CreatePersonaTokenPopup />
    </section>
</template>

<script>
    import FilePreview from '/resources/js/components/FilePreview/FilePreview'
	import Spotlight from '/resources/js/components/Spotlight/Spotlight'
	import TwoFactorRecoveryCodesPopup from '/resources/js/components/Others/TwoFactorRecoveryCodesPopup'
	import CreatePersonaTokenPopup from '/resources/js/components/Others/CreatePersonaTokenPopup'
	import TwoFactorSetupPopup from '/resources/js/components/Others/TwoFactorSetupPopup'
    import UserImageInput from '/resources/js/components/Others/UserImageInput'
	import SidebarNavigation from "../components/Sidebar/SidebarNavigation"
    import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
	import PanelNavigationUser from "./User/Components/PanelNavigationUser"
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import InfoBox from '/resources/js/components/Others/Forms/InfoBox'
    import PageHeader from '/resources/js/components/Others/PageHeader'
    import ColorLabel from '/resources/js/components/Others/ColorLabel'
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import { mapGetters } from 'vuex'

    export default {
        name: 'Settings',
        components: {
			FilePreview,
			Spotlight,
			TwoFactorRecoveryCodesPopup,
			CreatePersonaTokenPopup,
			PanelNavigationUser,
			TwoFactorSetupPopup,
			SidebarNavigation,
            UserImageInput,
            MobileHeader,
            ButtonBase,
            ColorLabel,
            PageHeader,
            Spinner,
            InfoBox,
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
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .page-detail-headline {
        display: flex;
        justify-content: space-between;
        margin-bottom: 50px;
        margin-top: 30px;
    }

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

    .dark-mode {
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

    @media only screen and (max-width: 690px) {

        .page-detail-headline {
            display: block;
            margin-bottom: 30px;
            margin-top: 10px;

            .headline-actions {
                margin-top: 20px;

                .upgrade-button {
                    width: 100%;
                }
            }
        }
    }
</style>
