<template>
    <div>
        <OptionGroup :title="$t('view')">
            <Option
                v-if="isList"
                @click.native="changePreview('grid')"
                :title="$t('preview_sorting.grid_view')"
                icon="grid"
            />
            <Option
                v-if="isGrid"
                @click.native="changePreview('list')"
                :title="$t('preview_sorting.list_view')"
                icon="list"
            />
        </OptionGroup>
        <OptionGroup :title="$t('sorting')">
            <Option
                @click.native.stop="sort('created_at')"
                :arrow="arrowForCreatedAtField"
                :title="$t('preview_sorting.sort_date')"
                icon="calendar"
            />
            <Option
                @click.native.stop="sort('name')"
                :arrow="arrowForNameField"
                :title="$t('preview_sorting.sort_alphabet')"
                icon="alphabet"
            />
        </OptionGroup>
    </div>
</template>

<script>
import OptionGroup from './Components/OptionGroup'
import Option from './Components/Option'
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
        ...mapGetters(['itemViewType']),
        isGrid() {
            return this.itemViewType === 'grid'
        },
        isList() {
            return this.itemViewType === 'list'
        },
        arrowForCreatedAtField() {
            if (this.filter.field !== 'created_at') return undefined

            return this.filter.sort === 'DESC' ? 'up' : 'down'
        },
        arrowForNameField() {
            if (this.filter.field !== 'name') return undefined

            return this.filter.sort === 'DESC' ? 'up' : 'down'
        },
    },
    data() {
        return {
            filter: {
                sort: 'DESC',
                field: undefined,
            },
        }
    },
    methods: {
        sort(field) {
            this.filter.field = field

            // Set sorting direction
            if (this.filter.sort === 'DESC') this.filter.sort = 'ASC'
            else if (this.filter.sort === 'ASC') this.filter.sort = 'DESC'

            // Save to localStorage sorting options
            localStorage.setItem(
                'sorting',
                JSON.stringify({
                    sort: this.filter.sort,
                    field: this.filter.field,
                })
            )

            this.$store.commit('START_LOADING_VIEW')

            // Update sorting state in vuex
            this.$store.commit('UPDATE_SORTING')

            // Get data of user with favourites tree
            this.$store.dispatch('getAppData')

            // Get data of Navigator tree
            this.$store.dispatch('getFolderTree')

            this.$getDataByLocation(1)
        },
        changePreview(previewType) {
            this.$store.dispatch('togglePreviewType', previewType)
        },
    },
    mounted() {
        let sorting = JSON.parse(localStorage.getItem('sorting'))

        // Set default sorting if in not setup in LocalStorage
        this.filter.sort = sorting ? sorting.sort : 'DESC'
        this.filter.field = sorting ? sorting.field : 'created_at'
    },
}
</script>
