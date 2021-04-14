<template>
    <MenuMobile name="user-navigation">
        <UserHeadline class="user-info" />

        <MenuMobileGroup>
            <OptionGroup>
                <Option @click.native="goToFiles" :title="$t('menu.files')" icon="hard-drive" />
                <Option @click.native="showUserProfileMenu" :title="$t('menu.settings')" icon="user" />
                <Option @click.native="goToAdmin" :title="$t('menu.admin')" icon="settings" v-if="isAdmin" />
                <Option @click.native="logOut" :title="$t('menu.logout')" icon="power" />
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
    import {mapGetters} from 'vuex'

    export default {
        name: 'MobileNavigation',
        components: {
            MenuMobileGroup,
            UserHeadline,
            OptionGroup,
            MenuMobile,
            Option,
        },
        computed: {
            ...mapGetters([
                'homeDirectory',
                'user',
            ]),
            isAdmin() {
                return this.user && this.user.data.attributes.role === 'admin'
            }
        },
        methods: {
            goToFiles() {
                this.$store.dispatch('getFolder', [{folder: this.homeDirectory, back: false, init: true}])
            },
            showUserProfileMenu() {

            },
            goToAdmin() {

            },
            logOut() {
                this.$store.dispatch('logOut')
            },
        }
    }
</script>

<style scoped lang="scss">

    .user-info {
        padding: 20px 20px 10px;
    }
</style>
