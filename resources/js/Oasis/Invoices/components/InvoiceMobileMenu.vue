<template>
    <MenuMobile name="invoice-menu">
		<TitlePreview
			v-if="clipboard[0]"
			class="headline"
			icon="file-text"
			:title="clipboard[0].name"
			:subtitle="'Invoice - ' + clipboard[0].invoice_number"
		/>

		<!--Trash location-->
        <MenuMobileGroup>
            <OptionGroup class="menu-option-group">
                <Option @click.native="" title="Edit Invoice" icon="rename" />
                <Option @click.native="" title="Send Invoice" icon="send" />
                <Option @click.native="goToCompany" title="Go to Company" icon="user" />
                <Option @click.native="deleteInvoice" :title="$t('context_menu.delete')" icon="trash" />
            </OptionGroup>

            <OptionGroup>
                <Option @click.native="downloadInvoice" :title="$t('context_menu.download')" icon="download" />
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
import {events} from '@/bus'

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
		downloadInvoice() {
			this.$downloadFile(this.clipboard[0].file_url, this.clipboard[0].name + '.' + this.clipboard[0].mimetype)
		},
		goToCompany() {
			this.$router.push({name: 'ClientDetail', params: {id: this.clipboard[0].client_id}})
		},
		deleteInvoice() {
			events.$emit('confirm:open', {
				title: `Are you sure you want to delete invoice number ${this.clipboard[0].invoice_number}?`,
				message: 'Your invoice will be permanently deleted.',
				buttonColor: 'danger-solid',
				action: {
					id: this.clipboard[0].id,
					operation: 'delete-invoice'
				}
			})
		},
    }
}
</script>

<style scoped lang="scss">

	.headline {
		padding: 20px 20px 10px;
		margin-bottom: 0;
	}
</style>
