<template>
    <MenuMobile name="invoice-sorting">
        <MenuMobileGroup>
            <OptionGroup>
				<Option @click.native.stop="sort('created_at')" :title="$t('preview_sorting.sort_date')" icon="calendar" />
				<Option @click.native.stop="sort('name')" :title="$t('preview_sorting.sort_alphabet')" icon="alphabet" />
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
    name: 'InvoiceSortingMobile',
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

    }
}
</script>
