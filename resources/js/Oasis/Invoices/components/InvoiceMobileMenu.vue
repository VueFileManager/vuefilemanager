<template>
    <MenuMobile name="invoice-menu">
		<TitlePreview
			v-if="clipboard[0]"
			class="headline"
			icon="file-text"
			:title="clipboard[0].name"
			:subtitle="$t('in.invoice') + ' - ' + clipboard[0].invoice_number"
		/>

		<!--Trash location-->
        <MenuMobileGroup>
            <OptionGroup class="menu-option-group">
                <Option @click.native="$editInvoice(clipboard[0])" :title="$t('in.menu.edit_invoice')" icon="rename" />
                <Option @click.native="$shareInvoice(clipboard[0])" :title="$t('in.menu.send_invoice')" icon="send" />
                <Option @click.native="$goToCompany(clipboard[0])" :title="$t('in.menu.show_company')" icon="user" />
                <Option @click.native="$deleteInvoice(clipboard[0])" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>
            <OptionGroup>
                <Option @click.native="$downloadInvoice(clipboard[0])" :title="$t('context_menu.download')" icon="download" />
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
    name: 'InvoiceMobileMenu',
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
    }
}
</script>

<style scoped lang="scss">

	.headline {
		padding: 20px 20px 10px;
		margin-bottom: 0;
	}
</style>
