<template>
    <div class="datatable">
        <table v-if="hasData" class="table">
            <thead class="table-header">
            <tr>
                <td
                        v-for="(column, index) in columns"
                        @click="sort(column.field, column.sortable, index)"
                        :key="index"
                        :class="{ sortable: column.sortable }"
                >
                    <span>{{ column.label }}</span>

                    <chevron-up-icon v-if="false" :class="{ 'arrow-down': filter.sort === 'ASC' }" size="14" class="filter-arrow"></chevron-up-icon>
                </td>
            </tr>
            </thead>

            <tbody class="table-body">
            <slot v-for="row in visibleData" :row="row">
                <DatatableCell :data="row" :key="row.id"/>
            </slot>
            </tbody>

        </table>
        <div v-if="hasData && paginator" class="paginator-wrapper">
            <ul v-if="chunks.length > 1" class="pagination">
                <li class="page-item">
                    <a
                            @click="goToPage(pageIndex - 1)"
                            class="page-link"
                            :class="{ disabled: pageIndex == 0 }"
                    >
                        <chevron-left-icon size="14" class="icon"></chevron-left-icon>
                    </a>
                </li>
                <li
                        v-for="(row, index) in chunks"
                        :key="index"
                        class="page-item"
                        @click="goToPage(index)"
                >
                    <a
                            class="page-link"
                            :class="{ active: pageIndex == index }">
                        {{ index + 1 }}
                    </a>
                </li>
                <li class="page-item">
                    <a
                            @click="goToPage(pageIndex + 1)"
                            class="page-link"
                            :class="{ disabled: pageIndex + 1 == chunks.length }"
                    >
                        <chevron-right-icon size="14" class="icon"></chevron-right-icon>
                    </a>
                </li>
            </ul>
            <span class="paginator-info">{{ $t('datatable.paginate_info', {visible: visibleData.length, total: data.length}) }}</span>
        </div>
    </div>
</template>

<script>
    import { ChevronUpIcon, ChevronLeftIcon, ChevronRightIcon } from 'vue-feather-icons'
    import DatatableCell from '@/components/Others/Tables/DatatableCell'
    import {chunk, sortBy} from 'lodash'

    export default {
        props: ['columns', 'data', 'scope', 'paginator'],
        components: {
            ChevronRightIcon,
            ChevronLeftIcon,
            DatatableCell,
            ChevronUpIcon,
        },
        data() {
            return {
                items_per_view: 20,
                pageIndex: 0,
                paginatorVisible: true,
                chunks: [],
                filter: {
                    sort: 'DESC',
                    field: undefined,
                    index: undefined,
                }
            }
        },
        methods: {
            goToPage(index) {
                if (index == this.chunks.length || index == -1) return

                // Update page index
                this.pageIndex = index
            },
            sort(field, sortable, index) {

                // Prevent sortable if is disabled
                if (!sortable) return

                // Set filter
                this.filter.field = field
                this.filter.index = index

                // Set sorting direction
                if (this.filter.sort === 'DESC') {
                    this.filter.sort = 'ASC'
                } else if (this.filter.sort === 'ASC') {
                    this.filter.sort = 'DESC'
                }
            }
        },
        computed: {
            hasData() {

                return this.data.length > 0 ? true : false
            },
            visibleData() {

                // Prefent errors when data is empty
                if (!this.hasData) return

                // Reconfigure data
                if (this.filter.field) {

                    // Set chunks with sorting
                    if (this.filter.sort === 'DESC') {

                        // DESC
                        this.chunks = chunk(sortBy(this.data, this.filter.field), this.items_per_view)
                    } else {

                        // ASC
                        this.chunks = chunk(sortBy(this.data, this.filter.field).reverse(), this.items_per_view)
                    }

                } else {

                    // Get data to chunks
                    this.chunks = chunk(this.data, this.items_per_view)
                }

                // Return chunks
                return this.chunks[this.pageIndex]
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .datatable {
        height: 100%;
    }

    .table-row {
        @include transition;
    }

    .table-row-enter,
    .table-row-leave-to {
        opacity: 0;
        @include transform(translateY(-100%));
    }

    .table-row-leave-active {
        position: absolute;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        overflow-x: auto;

        tr {
            width: 100%;

            td {
                &:first-child {
                    padding-left: 15px;
                }

                &:last-child {
                    padding-right: 15px;
                    text-align: right;
                }
            }
        }

        .table-header {
            margin-bottom: 10px;

            tr {
                td {
                    padding: 12px;

                    span {
                        color: #AFAFAF;
                        font-weight: 700;
                        @include font-size(12);
                        white-space: nowrap;
                    }

                    &.sortable {
                        cursor: pointer;
                    }

                    &:last-child {
                        text-align: right;
                    }
                }
            }

            .filter-arrow {
                vertical-align: middle;
                margin-left: 8px;
                @include transition;

                path {
                    fill: $text-muted;
                }

                &.arrow-down {
                    @include transform(rotate(180deg));
                }
            }

            span {
                font-size: 13px;
                color: $text-muted;
                font-weight: bold;
            }
        }

        .table-body {
            tr {
                border-radius: 8px;

                &:hover {
                    background: $light_background;
                }

                td {
                    padding: 12px;

                    &:last-child {
                        button {
                            margin-right: 0;
                        }
                    }
                }
            }

            span, a.page-link {
                @include font-size(15);
                font-weight: 700;
                padding: 10px 0;
                display: block;
            }
        }
    }

    .pagination {
        .page-item {
            padding: 3px;
            display: inline-block;
        }

        .page-link {
            width: 30px;
            height: 30px;
            display: block;
            color: $text;
            border-radius: 6px;
            text-align: center;
            line-height: 2.4;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            @include transition(0.15s);

            .icon {
                vertical-align: middle;
                margin-top: -2px;
            }

            &:hover:not(.disabled) {
                background: $light_background;
                color: $text;
            }

            &.active {
                color: $text;
                background: $light_background;
            }

            &.disabled {
                background: transparent;
                cursor: default;

                svg path {
                    fill: $text-muted;
                }
            }
        }
    }

    .paginator-wrapper {
        margin-top: 30px;
        margin-bottom: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;

        .paginator-info {
            font-size: 13px;
            color: $text-muted;
        }
    }

    .user-preview {
        display: flex;
        align-items: center;
        cursor: pointer;

        img {
            width: 45px;
            margin-right: 22px;
        }
    }

    @media only screen and (max-width: 690px) {
        .paginator-wrapper {
            display: block;
            text-align: center;

            .paginator-info {
                margin-top: 10px;
                display: block;
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .table {

            .table-header {

                tr {
                    td {

                        span {
                            color: $theme;
                        }
                    }
                }
            }

            .table-body {
                tr {
                    &:hover {
                        background: $dark_mode_foreground;
                    }
                }
            }
        }

        .paginator-wrapper {

            .paginator-info {
                color: $dark_mode_text_secondary;
            }
        }

        .pagination {

            .page-link {
                color: $dark_mode_text_secondary;

                svg polyline {
                    stroke: $dark_mode_text_primary;
                }

                &:hover:not(.disabled) {
                    color: $theme;
                    background: rgba($theme, 0.1);
                }

                &.active {
                    color: $theme;
                    background: rgba($theme, 0.1);
                }

                &.disabled {
                    background: transparent;
                    cursor: default;

                    svg polyline {
                        stroke: $dark_mode_text_secondary;
                    }
                }
            }
        }
    }
</style>
