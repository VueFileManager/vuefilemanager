<template>
    <MenuMobile name="user-navigation">

        <!--User avatar-->
        <UserHeadline v-if="!clickedSubmenu" class="user-info" />

        <!--Go back button-->
        <div v-if="clickedSubmenu" @click.stop="showSubmenu(undefined)" class="go-back">
            <chevron-left-icon size="19" class="text-theme" />
            <span class="title text-theme">{{ backTitle }}</span>
        </div>

        <!--Menu links-->
        <MenuMobileGroup>

            <!--Main navigation-->
            <OptionGroup v-if="!clickedSubmenu">
                <Option @click.native="goToFiles" :title="$t('menu.files')" icon="hard-drive" is-hover-disabled="true"/>
                <Option @click.native.stop="showSubmenu('settings')" :title="$t('menu.settings')" icon="user" :is-arrow-right="true" is-hover-disabled="true"/>
                <Option v-if="isAdmin" @click.native.stop="showSubmenu('admin')" :title="$t('menu.admin')" icon="settings" :is-arrow-right="true" is-hover-disabled="true"/>
            </OptionGroup>
            <OptionGroup v-if="!clickedSubmenu">
                <Option @click.native="logOut" :title="$t('menu.logout')" icon="power" is-hover-disabled="true" />
            </OptionGroup>

            <!--Submenu: User settings-->
            <OptionGroup v-if="clickedSubmenu === 'settings'">
                <Option @click.native="goToRoute('Profile')" :title="$t('menu.profile')" icon="user" is-hover-disabled="true" />
                <Option @click.native="goToRoute('Storage')" :title="$t('menu.storage')" icon="hard-drive" is-hover-disabled="true" />
                <Option @click.native="goToRoute('Password')" :title="$t('menu.password')" icon="lock" is-hover-disabled="true" />
            </OptionGroup>
            <OptionGroup v-if="clickedSubmenu === 'settings' && config.isSaaS">
                <Option v-if="" @click.native="goToRoute('Subscription')" :title="$t('menu.subscription')" icon="cloud" is-hover-disabled="true" />
                <Option @click.native="goToRoute('PaymentMethods')" :title="$t('menu.payment_cards')" icon="credit-card" is-hover-disabled="true" />
                <Option @click.native="goToRoute('Invoice')" :title="$t('menu.invoices')" icon="file-text" is-hover-disabled="true" />
            </OptionGroup>

            <!--Submenu: Admin settings-->
            <OptionGroup v-if="clickedSubmenu === 'admin'">
                <Option @click.native="goToRoute('Dashboard')" :title="$t('admin_menu.dashboard')" icon="box" is-hover-disabled="true" />
                <Option @click.native="goToRoute('Users')" :title="$t('admin_menu.users')" icon="users" is-hover-disabled="true" />
                <Option @click.native="goToRoute('AppOthers')" :title="$t('admin_menu.settings')" icon="settings" is-hover-disabled="true" />
                <Option @click.native="goToRoute('Pages')" :title="$t('admin_menu.pages')" icon="monitor" is-hover-disabled="true" />
                <Option @click.native="goToRoute('Language')" :title="$t('languages')" icon="globe" is-hover-disabled="true" />
            </OptionGroup>
            <OptionGroup v-if="clickedSubmenu === 'admin' && config.isSaaS">
                <Option v-if="" @click.native="goToRoute('Plans')" :title="$t('admin_menu.plans')" icon="database" is-hover-disabled="true" />
                <Option @click.native="goToRoute('Invoices')" :title="$t('admin_menu.invoices')" icon="file-text" is-hover-disabled="true" />
            </OptionGroup>
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
    import MenuMobileGroup from '@/components/Mobile/MenuMobileGroup'
    import OptionGroup from '@/components/FilesView/OptionGroup'
    import UserHeadline from '@/components/Sidebar/UserHeadline'
    import MenuMobile from '@/components/Mobile/MenuMobile'
    import Option from '@/components/FilesView/Option'
    import {ChevronLeftIcon} from 'vue-feather-icons'
    import {mapGetters} from 'vuex'

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
            ...mapGetters([
                'homeDirectory',
                'config',
                'user',
            ]),
            isAdmin() {
                return this.user && this.user.data.attributes.role === 'admin'
            },
            backTitle() {
                let location = {
                    'settings': this.$t('menu.settings'),
                    'admin': this.$t('menu.admin')
                }

                return 'Go back from ' + location[this.clickedSubmenu]
            }
        },
        data() {
            return {
                clickedSubmenu: undefined,
            }
        },
        methods: {
            goToRoute(route) {
                this.$router.push({name: route})
                this.clickedSubmenu = undefined
            },
            showSubmenu(name) {
                this.clickedSubmenu = name
            },
            goToFiles() {
                if (this.$route.name !== 'Files')
                    this.$router.push({name: 'Files'})

                this.$store.dispatch('getFolder', [{folder: this.homeDirectory, back: false, init: true}])
            },
            logOut() {
                this.$store.dispatch('logOut')
            },
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/vuefilemanager/_variables";
    @import "@assets/vuefilemanager/_mixins";

    .user-info {
        padding: 20px 20px 10px;
    }

    .go-back {
        display: flex;
        align-items: center;
        padding: 30px 20px 10px;
        cursor: pointer;

        .title {
            @include font-size(14);
            font-weight: 700;
            margin-left: 10px;
        }

        polyline {
            color: inherit;
        }
    }
</style>
