<template>
    <div class="datatable">
        <table v-if="hasData" class="table">
            <thead class="table-header">
            <tr>
                <th
                    v-for="(column, index) in columns"
                    @click="sort(column.field, column.sortable)"
                    :key="index"
                    :class="{ 'sortable': column.sortable }"
                    v-if="! column.hidden"
                >
                    <span>{{ column.label }}</span>

                    <chevron-up-icon v-if="column.sortable" :class="{ 'arrow-down': filter.sort === 'ASC' }" size="14" class="filter-arrow"></chevron-up-icon>
                </th>
            </tr>
            </thead>

            <tbody class="table-body">
            <slot v-for="row in data.data" :row="row">
                <DatatableCell :data="row" :key="row.id"/>
            </slot>
            </tbody>

        </table>

        <slot v-if="! isLoading  && ! hasData" name="empty-page"></slot>

        <div v-if="paginator && hasData" class="paginator-wrapper">

            <!--Show if there is only 6 pages-->
            <ul v-if="data.meta.total > 20 && data.meta.last_page <= 6" class="pagination">

                <!--Go previous icon-->
                <li class="page-item previous">
                    <a @click="goToPage(pageIndex - 1)" class="page-link" :class="{ disabled: pageIndex == 0 }">
                        <chevron-left-icon size="14" class="icon"></chevron-left-icon>
                    </a>
                </li>

                <li v-for="(page, index) in 6" :key="index" class="page-item" @click="goToPage(page)">
                    <a class="page-link" :class="{ active: pageIndex === page }">
                        {{ page }}
                    </a>
                </li>

                <!--Go next icon-->
                <li class="page-item next">
                    <a @click="goToPage(pageIndex + 1)" class="page-link" :class="{ disabled: pageIndex + 1 == data.meta.last_page }">
                        <chevron-right-icon size="14" class="icon"></chevron-right-icon>
                    </a>
                </li>
            </ul>

            <!--Show if there is more than 6 pages-->
            <ul v-if="data.meta.total > 20 && data.meta.last_page > 6" class="pagination">

                <!--Go previous icon-->
                <li class="page-item previous">
                    <a @click="goToPage(pageIndex - 1)" class="page-link" :class="{ disabled: pageIndex == 0 }">
                        <chevron-left-icon size="14" class="icon"></chevron-left-icon>
                    </a>
                </li>

                <!--Show first Page-->
                <li class="page-item" v-if="pageIndex >= 5" @click="goToPage(1)">
                    <a class="page-link">
                        1
                    </a>
                </li>

                <li v-if="pageIndex < 5" v-for="(page, index) in 5" :key="index" class="page-item" @click="goToPage(page)">
                    <a class="page-link" :class="{ active: pageIndex === page }">
                        {{ page }}
                    </a>
                </li>

                <li class="page-item" v-if="pageIndex >= 5">
                    <a class="page-link">...</a>
                </li>

                <!--Floated Pages-->
                <li v-if="pageIndex >= 5 && pageIndex < (data.meta.last_page - 3)" v-for="(page, index) in floatPages" :key="index" class="page-item" @click="goToPage(page)">
                    <a class="page-link" :class="{ active: pageIndex === page }">
                        {{ page }}
                    </a>
                </li>

                <li class="page-item" v-if="pageIndex < (data.meta.last_page - 3)">
                    <a class="page-link">...</a>
                </li>

                <li v-if="pageIndex > (data.meta.last_page - 4)" v-for="(page, index) in 5" :key="index" class="page-item" @click="goToPage(data.meta.last_page - (4 - index))">
                    <a class="page-link" :class="{ active: pageIndex === (data.meta.last_page - (4 - index)) }">
                        {{ data.meta.last_page - (4 - index) }}
                    </a>
                </li>

                <!--Show last page-->
                <li class="page-item" v-if="pageIndex < (data.meta.last_page - 3)" @click="goToPage(data.meta.last_page)">
                    <a class="page-link">
                        {{ data.meta.last_page }}
                    </a>
                </li>

                <!--Go next icon-->
                <li class="page-item next">
                    <a @click="goToPage(pageIndex + 1)" class="page-link" :class="{ disabled: pageIndex + 1 == data.meta.last_page }">
                        <chevron-right-icon size="14" class="icon"></chevron-right-icon>
                    </a>
                </li>
            </ul>


            <span class="paginator-info">{{ $t('datatable.paginate_info', {visible: data.meta.per_page, total: data.meta.total}) }}</span>
        </div>
    </div>
</template>

<script>
import {ChevronUpIcon, ChevronLeftIcon, ChevronRightIcon} from 'vue-feather-icons'
import DatatableCell from '@/components/Others/Tables/DatatableCell'
import {chunk, sortBy} from 'lodash'
import axios from "axios";

export default {
    name: 'DatatableWrapper',
    props: [
        'columns', 'scope', 'paginator', 'api', 'tableData'
    ],
    components: {
        ChevronRightIcon,
        ChevronLeftIcon,
        DatatableCell,
        ChevronUpIcon,
    },
    computed: {
        hasData() {
            return this.data && this.data.data && this.data.data.length > 0
        },
        floatPages() {
            return [(this.pageIndex - 1), this.pageIndex, (this.pageIndex + 1)];
        }
    },
    data() {
        return {
            data: undefined,
            isLoading: true,
            pageIndex: 1,
            filter: {
                sort: 'DESC',
                field: undefined,
            }
        }
    },
    methods: {
        goToPage(index) {
            if (index > this.data.meta.last_page || index === 0) return

            this.pageIndex = index

            this.getPage(index)
        },
        sort(field, sortable) {

            // Prevent sortable if is disabled
            if (!sortable) return

            // Set filter
            this.filter.field = field

            // Set sorting direction
            if (this.filter.sort === 'DESC') {
                this.filter.sort = 'ASC'
            } else if (this.filter.sort === 'ASC') {
                this.filter.sort = 'DESC'
            }

            this.getPage(this.pageIndex)
        },
        getPage(page) {

            // Get api URI
            this.URI = this.api;

            // Set page index
            if (this.paginator)
                this.URI = this.URI + '?page=' + page

            // Add filder URI if is defined sorting
            if (this.filter.field)

                this.URI = this.URI + (this.paginator ? '&' : '?') + 'sort=' + this.filter.field + '&direction=' + this.filter.sort

            this.isLoading = true

            // Get data
            axios.get(this.URI)
                .then(response => {
                    this.data = response.data
                    this.$emit('data', response.data)

                })
                .catch(() => this.$isSomethingWrong())
                .finally(() => {
                    this.$emit('init', true)
                    this.isLoading = false
                }
            )
        },
    },
    created() {
        if (this.api)
            this.getPage(this.pageIndex)

        if (this.tableData)
            this.data = this.tableData,
            this.isLoading = false
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

        td, th {
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
            td, th {
                padding: 12px;
                text-align: left;

                span {
                    color: $theme;
                    font-weight: 700;
                    @include font-size(12);
                    white-space: nowrap;
                }

                &.sortable {
                    cursor: pointer;

                    &:hover {
                        .filter-arrow {
                            opacity: 1;
                        }
                    }
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
            opacity: 0;

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
            //border-bottom: 1px solid #f5f5f5;

            &:hover {
                background: $light_background;
            }

            td, th {
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
            padding: 10px 35px 10px 0;
            display: block;
            white-space: nowrap;
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
                td, th {

                    span {
                        color: $theme;
                    }
                }
            }
        }

        .table-body {
            tr, th {
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
