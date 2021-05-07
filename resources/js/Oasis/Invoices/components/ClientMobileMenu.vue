<template>
    <MenuMobile name="client-menu">
		<TitlePreview
			v-if="clipboard[0]"
			class="headline"
			:avatar="clipboard[0].avatar"
			:title="clipboard[0].name"
			:subtitle="clipboard[0].email"
		/>
        <MenuMobileGroup>
            <OptionGroup class="menu-option-group">
                <Option @click.native="goToProfile" :title="$t('in.menu.edit')" icon="rename" />
                <Option @click.native="deleteItem" :title="$t('in.menu.delete')" icon="trash" />
            </OptionGroup>
            <OptionGroup>
                <Option @click.native="goToProfile" :title="$t('context_menu.detail')" icon="detail" />
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
		goToProfile() {
			this.$router.push({name: 'ClientDetail', params: {id: this.clipboard[0].id}})
		},
		deleteItem() {
			events.$emit('confirm:open', {
				title: this.$t('in.popup.delete_client.title', {name: this.clipboard[0].name}),
				message: this.$t('in.popup.delete_client.message'),
				buttonColor: 'danger-solid',
				action: {
					id: this.clipboard[0].id,
					operation: 'delete-client'
				}
			})
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
