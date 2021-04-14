<template>
    <MenuMobile name="file-sorting">
        <MenuMobileGroup>
            <FileSortingOptions />
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import FileSortingOptions from '@/components/FilesView/FileSortingOptions'
import MenuMobileGroup from '@/components/Mobile/MenuMobileGroup'
import MenuMobile from '@/components/Mobile/MenuMobile'

export default {
    name: 'FilterSortingMobile',
    components: {
        FileSortingOptions,
        MenuMobileGroup,
        MenuMobile,
    },
}
</script>
