<template>
    <div class="h-screen lg:overflow-hidden lg:flex w-full">
        <!--On Top of App Components-->
        <FilePreview />
        <Spotlight />

		<!--Spotlight Addons-->
		<CreateUploadRequestPopup />
		<CreateTeamFolderPopup />
		<NotificationsPopup />
		<RemoteUploadPopup />

        <ConfirmPopup />
        <ConfirmPassword />

        <!--2FA popups-->
        <TwoFactorRecoveryCodesPopup />
        <TwoFactorQrSetupPopup />

        <!--Access Token Popup-->
        <CreatePersonalTokenPopup />

        <!--Payments Popup-->
        <SubscribeAccountPopup v-if="config.subscriptionType === 'fixed'" />
		<ChangeSubscriptionPopup v-if="config.subscriptionType === 'fixed'" />
        <ChargePaymentPopup v-if="config.subscriptionType === 'metered'" />

        <!--Navigations-->
        <MobileNavigation />

        <MobileNavigationToolbar />

        <div
            v-if="user"
            class="relative mx-auto w-full overflow-x-hidden px-2.5 pb-12 md:max-w-4xl md:px-6 lg:pt-6 lg:pb-0 xl:max-w-(--breakpoint-lg) z-5"
        >
            <div v-if="!isLoading" id="page-content">
                <div class="card sticky top-0 z-10 shadow-card" style="padding-bottom: 0">
                    <!--User thumbnail-->
                    <div class="mb-3 flex items-center">
                        <!--Image input for replace avatar-->
                        <AvatarInput
                            v-model="avatar"
                            :avatar="user.data.relationships.settings.data.attributes.avatar ? user.data.relationships.settings.data.attributes.avatar.md : undefined"
                        />

                        <!--User name & email-->
                        <div class="ml-4">
                            <b class="text-md block font-bold sm:text-lg">
                                {{ user.data.relationships.settings.data.attributes.first_name }}
                                {{ user.data.relationships.settings.data.attributes.last_name }}

                                <ColorLabel v-if="config.subscriptionType === 'fixed'" :color="subscriptionColor">
                                    {{ subscriptionStatus }}
                                </ColorLabel>
                            </b>
                            <small class="block text-xs dark:text-gray-500 text-gray-600 sm:text-sm">
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
                <Spinner />
            </div>
        </div>
    </div>
</template>

<script>
import MobileNavigation from '../components/Mobile/MobileNavigation.vue'
import ChargePaymentPopup from '../components/Subscription/Popups/ChargePaymentPopup.vue'
import SubscribeAccountPopup from '../components/Subscription/Popups/SubscribeAccountPopup.vue'
import ConfirmPopup from '../components/Popups/ConfirmPopup.vue'
import FilePreview from '../components/FilePreview/FilePreview.vue'
import Spotlight from '../components/Spotlight/Spotlight.vue'
import TwoFactorRecoveryCodesPopup from '../components/TwoFactorSetup/TwoFactorRecoveryCodesPopup.vue'
import CreatePersonalTokenPopup from '../components/Popups/CreatePersonalTokenPopup.vue'
import TwoFactorQrSetupPopup from '../components/TwoFactorSetup/TwoFactorQrSetupPopup.vue'
import AvatarInput from '../components/Inputs/AvatarInput.vue'
import ColorLabel from '../components/UI/Labels/ColorLabel.vue'
import Spinner from '../components/UI/Others/Spinner.vue'
import { mapGetters } from 'vuex'
import CardNavigation from '../components/UI/Others/CardNavigation.vue'
import ConfirmPassword from '../components/TwoFactorSetup/ConfirmPassword.vue'
import MobileNavigationToolbar from '../components/Mobile/MobileNavigationToolbar.vue'
import CreateUploadRequestPopup from "../components/UploadRequest/CreateUploadRequestPopup.vue";
import CreateTeamFolderPopup from "../components/Teams/CreateTeamFolderPopup.vue";
import ChangeSubscriptionPopup from "../components/Subscription/Popups/ChangeSubscriptionPopup.vue";
import NotificationsPopup from "../components/Notifications/NotificationsPopup.vue";
import RemoteUploadPopup from "../components/RemoteUpload/RemoteUploadPopup.vue";

export default {
    name: 'Settings',
    components: {
		RemoteUploadPopup,
		NotificationsPopup,
		ChangeSubscriptionPopup,
		CreateTeamFolderPopup,
		CreateUploadRequestPopup,
        MobileNavigationToolbar,
        MobileNavigation,
        ConfirmPassword,
        ChargePaymentPopup,
        SubscribeAccountPopup,
        ConfirmPopup,
        CardNavigation,
        FilePreview,
        Spotlight,
        TwoFactorRecoveryCodesPopup,
        CreatePersonalTokenPopup,
        TwoFactorQrSetupPopup,
        AvatarInput,
        ColorLabel,
        Spinner,
    },
    computed: {
        ...mapGetters(['config', 'user']),
        subscriptionStatus() {
            return this.user.data.relationships.subscription ? this.$t('premium') : this.$t('free')
        },
        subscriptionColor() {
            return this.user.data.relationships.subscription ? 'green' : 'purple'
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
                    title: this.$t('storage'),
                    route: 'Storage',
                },
            ]

            // Push billing item if subscription is set
            if (this.config.subscriptionType !== 'none') {
                list.push({
                    title: this.$t('billing'),
                    route: 'Billing',
                })
            }

            return list
        },
    },
    data() {
        return {
            avatar: undefined,
            isLoading: false,
        }
    },
}
</script>
