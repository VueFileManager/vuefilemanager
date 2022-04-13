<template>
    <MenuMobile name="file-filter">
        <MenuMobileGroup>
            <OptionGroup :title="$t('base')">
                <Option
                    @click.native="goToFiles"
                    :title="$t('sidebar.home')"
                    icon="hard-drive"
                    :is-active="$isThisRoute($route, 'Files')"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToLatest"
                    :title="$t('menu.latest')"
                    icon="upload-cloud"
                    :is-active="$isThisRoute($route, 'RecentUploads')"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToShared"
                    :title="$t('publicly_shared')"
                    icon="share"
                    :is-active="$isThisRoute($route, 'MySharedItems')"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToTrash"
                    :title="$t('trash')"
                    icon="trash"
                    :is-active="$isThisRoute($route, 'Trash')"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
            <OptionGroup :title="$t('collaboration')">
                <Option
                    @click.native="goToTeamFolders"
                    :title="$t('team_folders')"
                    icon="users"
                    :is-active="$isThisRoute($route, 'TeamFolders')"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToSharedWithMe"
                    :title="$t('shared_with_me')"
                    icon="user-check"
                    :is-active="$isThisRoute($route, 'SharedWithMe')"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '../Mobile/MenuMobileGroup'
import OptionGroup from './Components/OptionGroup'
import MenuMobile from '../Mobile/MenuMobile'
import Option from './Components/Option'
import { mapGetters } from 'vuex'

export default {
    name: 'MobileContextMenu',
    components: {
        MenuMobileGroup,
        OptionGroup,
        MenuMobile,
        Option,
    },
    methods: {
        goToFiles() {
            this.$router.push({ name: 'Files' })
        },
        goToLatest() {
            this.$router.push({ name: 'RecentUploads' })
        },
        goToShared() {
            this.$router.push({ name: 'MySharedItems' })
        },
        goToTrash() {
            this.$router.push({ name: 'Trash' })
        },
        goToTeamFolders() {
            this.$router.push({ name: 'TeamFolders' })
        },
        goToSharedWithMe() {
            this.$router.push({ name: 'SharedWithMe' })
        },
    },
}
</script>
