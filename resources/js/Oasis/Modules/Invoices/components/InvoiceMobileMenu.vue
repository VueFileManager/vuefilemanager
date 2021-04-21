<template>
    <MenuMobile name="invoice-menu">
		<TitlePreview
			v-if="clipboard[0]"
			class="headline"
			icon="file-text"
			:title="clipboard[0].name"
			:subtitle="'Invoice - ' + clipboard[0].invoiceNumber"
		/>

		<!--Trash location-->
        <MenuMobileGroup>
            <OptionGroup class="menu-option-group">
                <Option @click.native="" title="Edit Invoice" icon="rename" />
                <Option @click.native="" title="Send Invoice" icon="send" />
                <Option @click.native="" title="Go to Company" icon="user" />
                <Option @click.native="" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option @click.native="" :title="$t('context_menu.detail')" icon="detail" />
                <Option @click.native="" :title="$t('context_menu.download')" icon="download" />
            </OptionGroup>
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '@/components/Mobile/MenuMobileGroup'
import TitlePreview from '@/components/FilesView/TitlePreview'
import ThumbnailItem from '@/components/Others/ThumbnailItem'
import OptionGroup from '@/components/FilesView/OptionGroup'
import MenuMobile from '@/components/Mobile/MenuMobile'
import Option from '@/components/FilesView/Option'
import {mapGetters} from 'vuex'

export default {
    name: 'FileMenuMobile',
    components: {
        MenuMobileGroup,
        ThumbnailItem,
		TitlePreview,
        OptionGroup,
        MenuMobile,
        Option,
    },
    computed: {
        ...mapGetters([
            'clipboard',
            'user',
        ]),
    },
    data() {
        return {
            isVisible: false,
        }
    },
    methods: {

    }
}
</script>

<style scoped lang="scss">

	.headline {
		padding: 20px 20px 10px;
		margin-bottom: 0;
	}
</style>
