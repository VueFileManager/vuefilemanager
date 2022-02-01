<template>
    <MenuMobile @click.native.capture="closeMenu" name="create-list">
        <MenuMobileGroup>
            <slot />
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '../Mobile/MenuMobileGroup'
import MenuMobile from '../Mobile/MenuMobile'
import { events } from '../../bus'

export default {
    name: 'MobileContextMenu',
    components: {
        MenuMobileGroup,
        MenuMobile,
    },
    methods: {
        closeMenu() {
            events.$emit('mobile-menu:hide')
        },
    },
}
</script>
