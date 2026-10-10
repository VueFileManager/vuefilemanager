<template>
    <MenuMobile name="file-sorting">
        <MenuMobileGroup>
            <FileSortingOptions />
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import FileSortingOptions from './FileSortingOptions.vue'
import MenuMobileGroup from '../Mobile/MenuMobileGroup.vue'
import MenuMobile from '../Mobile/MenuMobile.vue'

export default {
    name: 'FileSortingMobile',
    components: {
        FileSortingOptions,
        MenuMobileGroup,
        MenuMobile,
    },
}
</script>
