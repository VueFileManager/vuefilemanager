<template>
    <MenuMobile name="invoice-create">
        <MenuMobileGroup>
            <OptionGroup>
                <Option @click.native="showLocation('regular-invoice')" title="Create Invoice" icon="file-plus" is-hover-disabled="true" />
                <Option @click.native="showLocation('advance-invoice')" title="Create Advance Invoice" icon="clock" is-hover-disabled="true" />
            </OptionGroup>
            <OptionGroup>
                <Option @click.native="showLocation('clients')" title="Create Client" icon="user-plus" is-hover-disabled="true" />
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
    name: 'InvoiceFilterMobile',
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
		showLocation(location) {

		},
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
        },
        goToParticipantUploads() {
            this.$store.dispatch('getParticipantUploads')
            this.flushBrowseHistory()
        }
    }
}
</script>
