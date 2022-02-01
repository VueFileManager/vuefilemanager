<template>
    <MenuMobile name="file-menu">
        <ThumbnailItem class="m-5" :item="clipboard[0]" />

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
import MenuMobileGroup from '../Mobile/MenuMobileGroup'
import ThumbnailItem from '../Others/ThumbnailItem'
import MenuMobile from '../Mobile/MenuMobile'
import { mapGetters } from 'vuex'

export default {
    name: 'MobileContextMenu',
    components: {
        MenuMobileGroup,
        ThumbnailItem,
        MenuMobile,
    },
    computed: {
        ...mapGetters(['clipboard']),
    },
}
</script>
