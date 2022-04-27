<template>
    <MenuMobile name="user-navigation">
        <!--User avatar-->
        <UserHeadline v-if="!clickedSubmenu" class="p-5 pb-3" />

        <!--Go back button-->
        <div v-if="clickedSubmenu" @click.stop="showSubmenu(undefined)" class="flex items-center p-5 pb-4">
            <chevron-left-icon size="19" class="vue-feather text-theme mr-2 -ml-1" />
            <span class="text-theme text-sm font-bold">
                {{ backTitle }}
            </span>
        </div>

        <!--Menu links-->
        <MenuMobileGroup>
            <!--Main navigation-->
            <OptionGroup v-if="!clickedSubmenu">
                <Option
                    @click.native="goToFiles"
                    :title="$t('menu.files')"
                    icon="hard-drive"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native.stop="showSubmenu('settings')"
                    :title="$t('settings')"
                    icon="user"
                    arrow="right"
                    :is-hover-disabled="true"
                />
                <Option
                    v-if="isAdmin"
                    @click.native.stop="showSubmenu('admin')"
                    :title="$t('administration')"
                    icon="settings"
                    arrow="right"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
            <OptionGroup v-if="!clickedSubmenu">
                <Option @click.native="logOut" :title="$t('logout')" icon="power" :is-hover-disabled="true" />
            </OptionGroup>

            <!--Submenu: User settings-->
            <OptionGroup v-if="clickedSubmenu === 'settings'">
                <Option
                    @click.native="goToRoute('Profile')"
                    :title="$t('menu.profile')"
                    icon="user"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToRoute('Password')"
                    :title="$t('menu.password')"
                    icon="lock"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToRoute('Storage')"
                    :title="$t('storage')"
                    icon="hard-drive"
                    :is-hover-disabled="true"
                />
            </OptionGroup>

            <!--Submenu: Admin settings-->
            <OptionGroup v-if="clickedSubmenu === 'admin'">
                <Option
                    @click.native="goToRoute('Dashboard')"
                    :title="$t('dashboard')"
                    icon="box"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToRoute('Users')"
                    :title="$t('users')"
                    icon="users"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToRoute('AppOthers')"
                    :title="$t('settings')"
                    icon="settings"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToRoute('Language')"
                    :title="$t('languages')"
                    icon="globe"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from './MenuMobileGroup'
import OptionGroup from '../Menus/Components/OptionGroup'
import UserHeadline from '../UI/Others/UserHeadline'
import MenuMobile from './MenuMobile'
import Option from '../Menus/Components/Option'
import { ChevronLeftIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'MobileNavigation',
    components: {
        ChevronLeftIcon,
        MenuMobileGroup,
        UserHeadline,
        OptionGroup,
        MenuMobile,
        Option,
    },
    computed: {
        ...mapGetters(['config', 'user']),
        isAdmin() {
            return this.user && this.user.data.attributes.role === 'admin'
        },
        backTitle() {
            let location = {
                settings: this.$t('settings'),
                admin: this.$t('administration'),
            }

            return this.$t('go_back_from_x', {location: location[this.clickedSubmenu]})
        },
    },
    data() {
        return {
            clickedSubmenu: undefined,
        }
    },
    methods: {
        goToRoute(route) {
            this.$router.push({ name: route })
            this.clickedSubmenu = undefined
        },
        showSubmenu(name) {
            this.clickedSubmenu = name
        },
        goToFiles() {
            if (this.$route.name !== 'Files') this.$router.push({ name: 'Files' })

            this.$store.dispatch('getFolder')
        },
        logOut() {
            this.$store.dispatch('logOut')
        },
    },
}
</script>
