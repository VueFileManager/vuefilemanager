<template>
    <MenuMobile name="file-sorting">
        <MenuMobileGroup>
            <FileSortingOptions />
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import FileSortingOptions from './FileSortingOptions'
import MenuMobileGroup from '../Mobile/MenuMobileGroup'
import MenuMobile from '../Mobile/MenuMobile'

export default {
    name: 'FileSortingMobile',
    components: {
        FileSortingOptions,
        MenuMobileGroup,
        MenuMobile,
    },
}
</script>
