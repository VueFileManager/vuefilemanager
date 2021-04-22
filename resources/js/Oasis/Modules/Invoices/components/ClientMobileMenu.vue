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
                <Option @click.native="" title="Edit" icon="rename" />
                <Option @click.native="deleteItem" title="Delete" icon="trash" />
            </OptionGroup>
            <OptionGroup>
                <Option @click.native="showDetail" :title="$t('context_menu.detail')" icon="detail" />
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
