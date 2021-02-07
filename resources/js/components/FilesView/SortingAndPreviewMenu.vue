<template>
    <div class="menu-options" id="menu-list">
        <ul class="menu-option-group">
            <li v-if="isList" class="menu-option" @click="changePreview('grid')">
                <div class="icon">
                    <grid-icon size="17"/>
                </div>
                <div class="text-label">
                    {{ $t('preview_sorting.grid_view') }}
                </div>
            </li>
            <li v-if="isGrid" class="menu-option" @click="changePreview('list')">
                <div class="icon">
                    <list-icon size="17"/>
                </div>
                <div class="text-label">
                    {{ $t('preview_sorting.list_view') }}
                </div>
            </li>
        </ul>
        <ul class="menu-option-group">
            <li class="menu-option" @click.stop="sort('created_at')">
                <div class="icon">
                    <calendar-icon size="17"/>
                </div>
                <div class="text-label">
                    {{ $t('preview_sorting.sort_date') }}
                </div>
                <div class="show-icon">
                    <arrow-up-icon size="17" v-if="filter.field === 'created_at'" :class="{ 'arrow-down': filter.sort === 'ASC' }"/>
                </div>
            </li>
            <li class="menu-option" @click.stop="sort('name')">
                <div class="icon">
                    <alphabet-icon size="17" class="alphabet-icon"/>
                </div>
                <div class="text-label">
                    {{ $t('preview_sorting.sort_alphabet') }}
                </div>
                <div class="show-icon">
                    <arrow-up-icon size="17" v-if="filter.field === 'name'" :class="{ 'arrow-down': filter.sort === 'ASC' }"/>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>

import { CalendarIcon, ListIcon, GridIcon, ArrowUpIcon, CheckIcon } from 'vue-feather-icons'
import AlphabetIcon from '@/components/FilesView/Icons/AlphabetIcon'
import { mapGetters } from 'vuex'
import { events } from '@/bus'

export default {
    name: 'SortingAndPreviewMenu',
    components: {
        CalendarIcon,
        AlphabetIcon,
        ArrowUpIcon,
        CheckIcon,
        ListIcon,
        GridIcon
    },
    computed: {
        ...mapGetters(['FilePreviewType']),
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

            if (this.$isMobile())
                events.$emit('mobileSortingAndPreview', false)
                events.$emit('mobileSortingAndPreviewVignette', this.mobileSortingAndPreview)
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

<style scoped lang="scss">
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.show-icon {
    margin-left: auto;
    max-height: 19px;

    .arrow-down {
        @include transform(rotate(180deg));
    }
}

.menu-option {
    display: flex;

    .icon {
        margin-right: 20px;
        line-height: 0;

        .alphabet-icon {
            /deep/ line,
            /deep/ polyline {
                stroke: $text;
            }
        }

    }

    .text-label {
        @include font-size(16);
    }
}

.sorting-preview {
    min-width: 250px;
    position: absolute;
    z-index: 99;
    box-shadow: $shadow;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    right: 66px;
    top: 63px;

    &.showed {
        display: block;
    }
}

.menu-options {
    list-style: none;
    width: 100%;
    margin: 0;
    padding: 0;

    .menu-option-group {
        padding: 5px 0;
        border-bottom: 1px solid $light_mode_border;

        &:first-child {
            padding-top: 0;
        }

        &:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }
    }

    .menu-option {
        white-space: nowrap;
        font-weight: 700;
        @include font-size(14);
        padding: 15px 20px;
        cursor: pointer;
        width: 100%;
        color: $text;
    }
}

@media (prefers-color-scheme: dark) {
    .menu-options {
        .menu-option-group {
            border-color: $dark_mode_border_color;
        }

        .menu-option {
            color: $dark_mode_text_primary;

            .icon {
                .alphabet-icon {
                    /deep/ line,
                    /deep/ polyline {
                        stroke: $dark_mode_text_primary;
                    }
                }
            }
        }
    }
}
</style>
