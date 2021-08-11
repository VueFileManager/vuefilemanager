<template>
    <MenuMobile name="file-sorting">
        <MenuMobileGroup>
            <FileSortingOptions />
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import FileSortingOptions from '/resources/js/components/FilesView/FileSortingOptions'
import MenuMobileGroup from '/resources/js/components/Mobile/MenuMobileGroup'
import MenuMobile from '/resources/js/components/Mobile/MenuMobile'

export default {
    name: 'FilterSortingMobile',
    components: {
        FileSortingOptions,
        MenuMobileGroup,
        MenuMobile,
    },
}
</script>
