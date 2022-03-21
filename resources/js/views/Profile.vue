<template>
    <div class="md:h-screen md:overflow-hidden lg:flex w-full">
        <!--On Top of App Components-->
        <FilePreview />
        <Spotlight />

		<!--Spotlight Addons-->
		<CreateUploadRequestPopup />
		<CreateTeamFolderPopup />
		<NotificationsPopup />

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
            class="relative mx-auto w-full overflow-x-hidden px-2.5 pb-12 md:max-w-4xl md:px-6 lg:pt-6 lg:pb-0 xl:max-w-screen-lg z-[5]"
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
import MobileNavigation from '../components/Others/MobileNavigation'
import ChargePaymentPopup from '../components/Others/ChargePaymentPopup'
import SubscribeAccountPopup from '../components/Subscription/SubscribeAccountPopup'
import ConfirmPopup from '../components/Others/Popup/ConfirmPopup'
import FilePreview from '../components/FilePreview/FilePreview'
import Spotlight from '../components/Spotlight/Spotlight'
import TwoFactorRecoveryCodesPopup from '../components/Others/TwoFactorRecoveryCodesPopup'
import CreatePersonalTokenPopup from '../components/Others/CreatePersonalTokenPopup'
import TwoFactorQrSetupPopup from '../components/Others/TwoFactorQrSetupPopup'
import AvatarInput from '../components/Others/Forms/AvatarInput'
import ColorLabel from '../components/Others/ColorLabel'
import Spinner from '../components/FilesView/Spinner'
import { mapGetters } from 'vuex'
import CardNavigation from '../components/Admin/CardNavigation'
import ConfirmPassword from '../components/Others/ConfirmPassword'
import MobileNavigationToolbar from '../components/Mobile/MobileNavigationToolbar'
import CreateUploadRequestPopup from "../components/Others/CreateUploadRequestPopup";
import CreateTeamFolderPopup from "../components/Teams/CreateTeamFolderPopup";
import ChangeSubscriptionPopup from "../components/Subscription/ChangeSubscriptionPopup";
import NotificationsPopup from "../components/Others/NotificationsPopup";

export default {
    name: 'Settings',
    components: {
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
