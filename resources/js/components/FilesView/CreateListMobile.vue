<template>
    <MenuMobile name="create-list">
        <MenuMobileGroup>
            <OptionGroup>
				<OptionUpload :class="{'is-inactive': canUploadInView || !hasCapacity }" :title="$t('actions.upload')" is-hover-disabled="true"/>
				<Option @click.native="createFolder" :class="{'is-inactive': canCreateFolderInView }" :title="$t('actions.create_folder')" icon="folder-plus" is-hover-disabled="true"/>
            </OptionGroup>
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '/resources/js/components/Mobile/MenuMobileGroup'
import OptionUpload from '/resources/js/components/FilesView/OptionUpload'
import OptionGroup from '/resources/js/components/FilesView/OptionGroup'
import MenuMobile from '/resources/js/components/Mobile/MenuMobile'
import Option from '/resources/js/components/FilesView/Option'
import {mapGetters} from 'vuex'
import {events} from '/resources/js/bus'

export default {
    name: 'FileMenuMobile',
    components: {
        MenuMobileGroup,
		OptionUpload,
        OptionGroup,
        MenuMobile,
        Option,
    },
	computed: {
		canUploadInView() {
			return !this.$isThisLocation(['base', 'public'])
		},
		hasCapacity() {
			// Check if storage limitation is set
			if (!this.$store.getters.config.storageLimit) return true

			// Check if user is loaded
			if (!this.$store.getters.user) return true

			// Check if user has storage
			return this.$store.getters.user.data.attributes.storage.used <= 100
		},
		canCreateFolderInView() {
			return !this.$isThisLocation(['base', 'public'])
		},
	},
    methods: {
		createFolder() {
			events.$emit('popup:open', {name: 'create-folder'})
		},
    }
}
</script>
