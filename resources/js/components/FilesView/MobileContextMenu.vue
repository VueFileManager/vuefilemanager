<template>
    <MenuMobile name="file-menu">
        <ThumbnailItem class="mb-5" :item="clipboard[0]" info="metadata" />

		<MenuMobileGroup v-if="$slots.default">
			<slot></slot>
        </MenuMobileGroup>

		<MenuMobileGroup v-if="$slots.editor && $checkPermission('editor')">
			<slot name="editor"></slot>
        </MenuMobileGroup>

		<MenuMobileGroup v-if="$slots.visitor && $checkPermission('visitor')">
			<slot name="visitor"></slot>
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '/resources/js/components/Mobile/MenuMobileGroup'
import ThumbnailItem from '/resources/js/components/Others/ThumbnailItem'
import MenuMobile from '/resources/js/components/Mobile/MenuMobile'
import {mapGetters} from 'vuex'

export default {
    name: 'MobileContextMenu',
    components: {
        MenuMobileGroup,
        ThumbnailItem,
		MenuMobile,
    },
    computed: {
        ...mapGetters([
            'clipboard',
        ]),
    },
}
</script>

<style scoped lang="scss">
@import "resources/sass/vuefilemanager/_variables";
@import "resources/sass/vuefilemanager/_mixins";

.item-thumbnail {
    padding: 20px 20px 10px;
    margin-bottom: 0;
}
</style>
