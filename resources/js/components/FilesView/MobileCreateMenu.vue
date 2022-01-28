<template>
    <MenuMobile @click.native.capture="closeMenu" name="create-list">
        <MenuMobileGroup>
			<slot />
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '/resources/js/components/Mobile/MenuMobileGroup'
import MenuMobile from '/resources/js/components/Mobile/MenuMobile'
import {events} from "../../bus";

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
    }
}
</script>
