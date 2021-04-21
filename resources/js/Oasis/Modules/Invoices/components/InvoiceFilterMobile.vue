<template>
    <MenuMobile name="invoice-filter">
        <MenuMobileGroup>
            <OptionGroup>
                <Option @click.native="showLocation('invoices')" :is-active="$isThisLocation('invoices')" title="Invoices" icon="file-text" is-hover-disabled="true" />
                <Option @click.native="showLocation('advance-invoices')" :is-active="$isThisLocation('advance-invoices')" title="Advance Invoices" icon="clock" is-hover-disabled="true" />
            </OptionGroup>
            <OptionGroup>
                <Option @click.native="showLocation('clients')" :is-active="$isThisLocation('clients')" title="Clients" icon="users" is-hover-disabled="true" />
            </OptionGroup>
        </MenuMobileGroup>
    </MenuMobile>
</template>

<script>
import MenuMobileGroup from '@/components/Mobile/MenuMobileGroup'
import OptionGroup from '@/components/FilesView/OptionGroup'
import MenuMobile from '@/components/Mobile/MenuMobile'
import Option from '@/components/FilesView/Option'
import {mapGetters} from 'vuex'

export default {
    name: 'InvoiceFilterMobile',
    components: {
        MenuMobileGroup,
        OptionGroup,
        MenuMobile,
        Option,
    },
    computed: {
        ...mapGetters([
            'homeDirectory'
        ]),
    },
    methods: {
		showLocation(location) {
			let routes = {
				'invoices': 'getInvoices',
				'advance-invoices': 'getAdvanceInvoices',
				'clients': 'getClients',
			}
			this.$store.dispatch(routes[location])
		}
    }
}
</script>
