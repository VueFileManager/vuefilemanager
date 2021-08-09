<template>
    <MenuMobile name="file-filter">
        <MenuMobileGroup>
            <OptionGroup>
                <Option @click.native="goToFiles" :title="$t('menu.files')" icon="hard-drive" :is-active="$isThisLocation('base')" is-hover-disabled="true" />
                <Option @click.native="goToLatest" :title="$t('menu.latest')" icon="upload-cloud" :is-active="$isThisLocation('latest')" is-hover-disabled="true" />
                <Option @click.native="goToShared" :title="$t('sidebar.my_shared')" icon="share" :is-active="$isThisLocation('shared')" is-hover-disabled="true" />
                <Option @click.native="goToTrash" :title="$t('menu.trash')" icon="trash" :is-active="$isThisLocation(['trash', 'trash-root'])" is-hover-disabled="true" />
            </OptionGroup>
            <OptionGroup>
				<!--todo: add teams-->
            </OptionGroup>
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '@/components/Mobile/MenuMobileGroup'
import OptionGroup from '@/components/FilesView/OptionGroup'
import MenuMobile from '@/components/Mobile/MenuMobile'
import Option from '@/components/FilesView/Option'
import {mapGetters} from 'vuex'

export default {
    name: 'FileMenuMobile',
    components: {
        MenuMobileGroup,
        OptionGroup,
        MenuMobile,
        Option,
    },
    computed: {
        ...mapGetters([
            'homeDirectory'
        ]),
    },
    methods: {
        flushBrowseHistory() {
            this.$store.commit('FLUSH_FOLDER_HISTORY')
        },
        goToFiles() {
            this.$store.dispatch('getFolder', [{folder: this.homeDirectory, back: false, init: true}])
            this.flushBrowseHistory()
        },
        goToLatest() {
            this.$store.dispatch('getLatest')
            this.flushBrowseHistory()
        },
        goToTrash() {
            this.$store.dispatch('getTrash')
            this.flushBrowseHistory()
        },
        goToShared() {
            this.$store.dispatch('getShared', [{back: false, init: false}])
            this.flushBrowseHistory()
        }
    }
}
</script>
