<template>
    <MenuMobile name="file-filter">
        <MenuMobileGroup>
            <OptionGroup :title="$t('Base')">
                <Option
                    @click.native="goToFiles"
                    :title="$t('sidebar.home')"
                    icon="hard-drive"
                    :is-active="$isThisRoute('Files')"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToLatest"
                    :title="$t('menu.latest')"
                    icon="upload-cloud"
                    :is-active="$isThisRoute('RecentUploads')"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToShared"
                    :title="$t('sidebar.my_shared')"
                    icon="share"
                    :is-active="$isThisRoute('MySharedItems')"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToTrash"
                    :title="$t('menu.trash')"
                    icon="trash"
                    :is-active="$isThisRoute('Trash')"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
            <OptionGroup :title="$t('Collaboration')">
                <Option
                    @click.native="goToTeamFolders"
                    :title="$t('Team Folders')"
                    icon="users"
                    :is-active="$isThisRoute('TeamFolders')"
                    :is-hover-disabled="true"
                />
                <Option
                    @click.native="goToSharedWithMe"
                    :title="$t('Shared with Me')"
                    icon="user-check"
                    :is-active="$isThisRoute('SharedWithMe')"
                    :is-hover-disabled="true"
                />
            </OptionGroup>
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '../Mobile/MenuMobileGroup'
import OptionGroup from '../FilesView/OptionGroup'
import MenuMobile from '../Mobile/MenuMobile'
import Option from '../FilesView/Option'
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
