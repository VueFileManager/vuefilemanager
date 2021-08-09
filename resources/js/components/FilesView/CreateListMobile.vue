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
import MenuMobileGroup from '@/components/Mobile/MenuMobileGroup'
import OptionUpload from '@/components/FilesView/OptionUpload'
import OptionGroup from '@/components/FilesView/OptionGroup'
import MenuMobile from '@/components/Mobile/MenuMobile'
import Option from '@/components/FilesView/Option'
import {mapGetters} from 'vuex'
import {events} from '@/bus'

export default {
    name: 'FileMenuMobile',
    components: {
        MenuMobileGroup,
		OptionUpload,
        OptionGroup,
        MenuMobile,
        Option,
    },
    methods: {
		createFolder() {
			events.$emit('popup:open', {name: 'create-folder'})
		},
    }
}
</script>
