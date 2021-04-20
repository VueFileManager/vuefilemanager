<template>
    <div>
        <OptionGroup>
            <Option v-if="isList" @click.native="changePreview('grid')" :title="$t('preview_sorting.grid_view')" icon="grid" />
            <Option v-if="isGrid" @click.native="changePreview('list')" :title="$t('preview_sorting.list_view')" icon="list" />
        </OptionGroup>
        <OptionGroup>
            <Option @click.native.stop="sort('created_at')" :title="$t('preview_sorting.sort_date')" icon="calendar" />
            <Option @click.native.stop="sort('name')" :title="$t('preview_sorting.sort_alphabet')" icon="alphabet" />
        </OptionGroup>

        <!-- TODO: implementovat sipky
        <arrow-up-icon size="17" v-if="filter.field === 'created_at'" :class="{ 'arrow-down': filter.sort === 'ASC' }"/>
        <arrow-up-icon size="17" v-if="filter.field === 'name'" :class="{ 'arrow-down': filter.sort === 'ASC' }"/>-->
    </div>
</template>

<script>

import OptionGroup from '@/components/FilesView/OptionGroup'
import Option from '@/components/FilesView/Option'
import { ArrowUpIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'FileSortingOptions',
    components: {
        OptionGroup,
        ArrowUpIcon,
        Option,
    },
    computed: {
        ...mapGetters([
            'FilePreviewType'
        ]),
        isGrid() {
            return this.FilePreviewType === 'grid'
        },
        isList() {
            return this.FilePreviewType === 'list'
        }
    },
    data() {
        return {
            filter: {
                sort: 'DESC',
                field: undefined
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
            localStorage.setItem('sorting', JSON.stringify({ sort: this.filter.sort, field: this.filter.field }))

            // Update sorting state in vuex
            this.$store.commit('UPDATE_SORTING')

            // Get data using the application location
            this.$getDataByLocation()
        },
        changePreview(previewType) {
            this.$store.dispatch('changePreviewType', previewType)
        }
    },
    mounted() {
        let sorting = JSON.parse(localStorage.getItem('sorting'))

        // Set default sorting if in not setup in LocalStorage
        this.filter.sort = sorting ? sorting.sort : 'DESC'
        this.filter.field = sorting ? sorting.field : 'created_at'
    }
}
</script>
