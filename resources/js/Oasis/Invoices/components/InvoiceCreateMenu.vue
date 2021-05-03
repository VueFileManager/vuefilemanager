<template>
    <MenuMobile name="invoice-create">
        <MenuMobileGroup>
            <OptionGroup>
                <Option @click.native="createInvoice('regular-invoice')" title="Create Invoice" icon="file-plus" is-hover-disabled="true" />
                <Option @click.native="createInvoice('advance-invoice')" title="Create Advance Invoice" icon="clock" is-hover-disabled="true" />
            </OptionGroup>
            <OptionGroup>
                <Option @click.native="createClient" title="Create Client" icon="user-plus" is-hover-disabled="true" />
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
		createInvoice(type) {
			this.$router.push({name: 'CreateInvoice', query: {type: type}})
		},
		createClient() {
			this.$router.push({name: 'CreateClient'})
		},
    }
}
</script>
