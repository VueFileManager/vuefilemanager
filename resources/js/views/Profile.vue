<template>
    <div class="h-screen lg:overflow-hidden lg:flex w-full">
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
import MobileNavigation from '../components/Mobile/MobileNavigation'
import ConfirmPopup from '../components/Popups/ConfirmPopup'
import FilePreview from '../components/FilePreview/FilePreview'
import Spotlight from '../components/Spotlight/Spotlight'
import TwoFactorRecoveryCodesPopup from '../components/TwoFactorSetup/TwoFactorRecoveryCodesPopup'
import CreatePersonalTokenPopup from '../components/Popups/CreatePersonalTokenPopup'
import TwoFactorQrSetupPopup from '../components/TwoFactorSetup/TwoFactorQrSetupPopup'
import AvatarInput from '../components/Inputs/AvatarInput'
import ColorLabel from '../components/UI/Labels/ColorLabel'
import Spinner from '../components/UI/Others/Spinner'
import { mapGetters } from 'vuex'
import CardNavigation from '../components/UI/Others/CardNavigation'
import ConfirmPassword from '../components/TwoFactorSetup/ConfirmPassword'
import MobileNavigationToolbar from '../components/Mobile/MobileNavigationToolbar'

export default {
    name: 'Settings',
    components: {
        MobileNavigationToolbar,
        MobileNavigation,
        ConfirmPassword,
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
        pages() {
            return [
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
