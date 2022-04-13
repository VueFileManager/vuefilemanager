<template>
    <div class="w-full">
        <table v-if="hasData" class="w-full">
            <thead>
                <tr class="whitespace-nowrap">
                    <th
                        class="text-left"
                        v-for="(column, index) in columns"
                        @click="sort(column.field, column.sortable)"
                        :key="index"
                        :class="{
                            'sortable cursor-pointer': column.sortable,
                            'text-right': Object.values(columns).length - 1 === index,
                        }"
                        v-if="!column.hidden"
                    >
                        <span class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $t(column.label) }}
                        </span>

                        <chevron-up-icon
                            v-if="column.sortable"
                            :class="{ 'arrow-down': filter.sort === 'ASC' }"
                            class="vue-feather inline-block text-gray-300 dark:text-gray-500"
                            size="12"
                        />
                    </th>
                </tr>
            </thead>

            <tbody class="table-body">
                <slot v-for="row in data.data" :row="row">
                    <DatatableCell :data="row" :key="row.id" />
                </slot>
            </tbody>
        </table>

        <!--Empty data slot-->
        <slot v-if="!isLoading && !hasData" name="empty-page" />

        <!--Paginator-->
        <div v-if="paginator && hasData" class="mt-6 sm:flex sm:items-center sm:justify-between">
            <!--Show if there is only 6 pages-->
            <ul v-if="data.meta.total > 15 && data.meta.last_page <= 6" class="pagination flex justify-center items-center">
                <!--Go previous icon-->
                <li class="previous inline-block p-1">
                    <a
                        @click="goToPage(pageIndex - 1)"
                        class="page-link"
                        :class="{
                            'cursor-default opacity-20': pageIndex === 1,
                        }"
                    >
                        <chevron-left-icon size="14" class="inline-block" />
                    </a>
                </li>

                <li
                    v-for="(page, index) in data.meta.last_page"
                    :key="index"
                    class="inline-block p-1"
                    @click="goToPage(page)"
                >
                    <a
                        class="page-link"
                        :class="{
                            'bg-light-background dark:bg-4x-dark-foreground dark:text-gray-300': pageIndex === page,
                        }"
                    >
                        {{ page }}
                    </a>
                </li>

                <!--Go next icon-->
                <li class="next inline-block p-1">
                    <a
                        @click="goToPage(pageIndex + 1)"
                        class="page-link"
                        :class="{
                            'cursor-default opacity-20': pageIndex === data.meta.last_page,
                        }"
                    >
                        <chevron-right-icon size="14" class="inline-block" />
                    </a>
                </li>
            </ul>

            <!--Show if there is more than 6 pages-->
            <ul v-if="data.meta.total > 15 && data.meta.last_page > 6" class="pagination flex justify-center items-center">
                <!--Go previous icon-->
                <li class="previous inline-block p-1">
                    <a
                        @click="goToPage(pageIndex - 1)"
                        class="page-link"
                        :class="{
                            'cursor-default opacity-20': pageIndex === 1,
                        }"
                    >
                        <chevron-left-icon size="14" class="inline-block" />
                    </a>
                </li>

                <!--Show first Page-->
                <li class="inline-block p-1" v-if="pageIndex >= 5" @click="goToPage(1)">
                    <a class="page-link"> 1 </a>
                </li>

                <li
                    v-if="pageIndex < 5"
                    v-for="(page, index) in 5"
                    :key="index"
                    class="inline-block p-1"
                    @click="goToPage(page)"
                >
                    <a
                        class="page-link"
                        :class="{
                            'bg-light-background dark:bg-4x-dark-foreground dark:text-gray-300': pageIndex === page,
                        }"
                    >
                        {{ page }}
                    </a>
                </li>

                <li class="inline-block p-1" v-if="pageIndex >= 5">
                    <a class="page-link">...</a>
                </li>

                <!--Floated Pages-->
                <li
                    v-if="pageIndex >= 5 && pageIndex < data.meta.last_page - 3"
                    v-for="(page, index) in floatPages"
                    :key="index"
                    class="inline-block p-1"
                    @click="goToPage(page)"
                >
                    <a
                        class="page-link"
                        :class="{
                            'bg-light-background dark:bg-4x-dark-foreground dark:text-gray-300': pageIndex === page,
                        }"
                    >
                        {{ page }}
                    </a>
                </li>

                <li class="inline-block p-1" v-if="pageIndex < data.meta.last_page - 3">
                    <a class="page-link">...</a>
                </li>

                <li
                    v-if="pageIndex > data.meta.last_page - 4"
                    v-for="(page, index) in 5"
                    :key="index"
                    class="inline-block p-1"
                    @click="goToPage(data.meta.last_page - (4 - index))"
                >
                    <a
                        class="page-link"
                        :class="{
                            'bg-light-background dark:bg-4x-dark-foreground dark:text-gray-300':
                                pageIndex === data.meta.last_page - (4 - index),
                        }"
                    >
                        {{ data.meta.last_page - (4 - index) }}
                    </a>
                </li>

                <!--Show last page-->
                <li
                    class="inline-block p-1"
                    v-if="pageIndex < data.meta.last_page - 3"
                    @click="goToPage(data.meta.last_page)"
                >
                    <a class="page-link">
                        {{ data.meta.last_page }}
                    </a>
                </li>

                <!--Go next icon-->
                <li class="next inline-block p-1">
                    <a
                        @click="goToPage(pageIndex + 1)"
                        class="page-link"
                        :class="{
                            'cursor-default opacity-20': pageIndex === data.meta.last_page,
                        }"
                    >
                        <chevron-right-icon size="14" class="inline-block" />
                    </a>
                </li>
            </ul>

            <span class="text-xs text-gray-600 dark:text-gray-500 block text-center sm:mt-0 mt-4">
				{{ $t('paginator', {from: data.meta.from, to: data.meta.to, total: data.meta.total}) }}
            </span>
        </div>
    </div>
</template>

<script>
import { ChevronUpIcon, ChevronLeftIcon, ChevronRightIcon } from 'vue-feather-icons'
import DatatableCell from './DatatableCell'
import axios from 'axios'

export default {
    name: 'DatatableWrapper',
    props: ['paginator', 'tableData', 'columns', 'scope', 'api'],
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
            return [this.pageIndex - 1, this.pageIndex, this.pageIndex + 1]
        },
    },
    data() {
        return {
            data: undefined,
            isLoading: true,
            pageIndex: 1,
            filter: {
                sort: 'DESC',
                field: undefined,
            },
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
            this.URI = this.api

            // Set page index
            if (this.paginator) this.URI = this.URI + '?page=' + page

            // Add filder URI if is defined sorting
            if (this.filter.field)
                this.URI =
                    this.URI +
                    (this.paginator ? '&' : '?') +
                    'sort=' +
                    this.filter.field +
                    '&direction=' +
                    this.filter.sort

            this.isLoading = true

            // Get data
            axios
                .get(this.URI)
                .then((response) => {
                    this.data = response.data
                    this.$emit('data', response.data)
                })
                .catch(() => this.$isSomethingWrong())
                .finally(() => {
                    this.$emit('init', true)
                    this.isLoading = false
                })
        },
    },
    created() {
        if (this.api) this.getPage(this.pageIndex)

        if (this.tableData) (this.data = this.tableData), (this.isLoading = false)
    },
}
</script>
