<template>
    <div>
		<OptionGroup v-if="$isThisLocation(['regular-invoice', 'advance-invoice'])">
			<Option @click.native.stop="sort('created_at')" :arrow="arrowForCreatedAtField" :title="$t('preview_sorting.sort_date')" icon="calendar" />
			<Option @click.native.stop="sort('total_net')" :arrow="arrowForTotalNetField" :title="$t('in.sort_by_net')" icon="dollar" />
			<Option @click.native.stop="sort('invoice_number')" :arrow="arrowForInvoiceNumberField" :title="$t('in.sort_by_invoice_number')" icon="file-text" />
		</OptionGroup>
		<OptionGroup v-if="$isThisLocation('clients')">
			<Option @click.native.stop="sort('created_at')" :title="$t('preview_sorting.sort_date')" icon="calendar" />
			<Option @click.native.stop="sort('name')" :title="$t('preview_sorting.sort_alphabet')" icon="alphabet" />
		</OptionGroup>
    </div>
</template>

<script>

import OptionGroup from '@/components/FilesView/OptionGroup'
import Option from '@/components/FilesView/Option'
import { ArrowUpIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'InvoiceSortingOptions',
    components: {
        OptionGroup,
        ArrowUpIcon,
        Option,
    },
    computed: {
		arrowForCreatedAtField() {
			if (this.filter.field !== 'created_at')
				return undefined

			return this.filter.sort === 'DESC' ? 'up' : 'down'
		},
		arrowForTotalNetField() {
			if (this.filter.field !== 'total_net')
				return undefined

			return this.filter.sort === 'DESC' ? 'up' : 'down'
		},
		arrowForInvoiceNumberField() {
			if (this.filter.field !== 'invoice_number')
				return undefined

			return this.filter.sort === 'DESC' ? 'up' : 'down'
		},
    },
    data() {
        return {
            filter: {
                sort: 'DESC',
                field: 'created_at'
            }
        }
    },
    methods: {
		sort(field) {
			this.filter.field = field

			// Set sorting direction
			if (this.filter.sort === 'DESC')
				this.filter.sort = 'ASC'
			else if (this.filter.sort === 'ASC')
				this.filter.sort = 'DESC'

			// Save to localStorage sorting options
			localStorage.setItem('sorting-invoices', JSON.stringify({ sort: this.filter.sort, field: this.filter.field }))

			// Update sorting state in vuex
			this.$store.commit('UPDATE_INVOICE_SORTING')

			// Get data using the application location
			this.$getInvoiceDataByLocation()
		},
    },
    mounted() {
        let sorting = JSON.parse(localStorage.getItem('sorting-invoices'))

        // Set default sorting if in not setup in LocalStorage
        this.filter.sort = sorting ? sorting.sort : 'DESC'
        this.filter.field = sorting ? sorting.field : 'created_at'
    }
}
</script>
