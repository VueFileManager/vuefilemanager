<template>
    <MenuMobile name="invoice-sorting">
        <MenuMobileGroup>
			<InvoiceSortingOptions />
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import InvoiceSortingOptions from '@/Oasis/Invoices/components/InvoiceSortingOptions'
import MenuMobileGroup from '@/components/Mobile/MenuMobileGroup'
import MenuMobile from '@/components/Mobile/MenuMobile'

export default {
    name: 'InvoiceSortingMobile',
    components: {
		InvoiceSortingOptions,
        MenuMobileGroup,
        MenuMobile,
        Option,
    },
}
</script>
