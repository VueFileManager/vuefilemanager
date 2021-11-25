<template>
    <div class="w-full">
        <table v-if="hasData" class="w-full">
            <thead class="">
				<tr>
					<th
						class="text-left"
						v-for="(column, index) in columns"
						@click="sort(column.field, column.sortable)"
						:key="index"
						:class="{ 'sortable': column.sortable, 'text-right': (Object.values(columns).length - 1) === index }"
						v-if="! column.hidden"
					>
						<span class="dark:text-gray-500 text-gray-400 text-xs">
							{{ column.label }}
						</span>

						<chevron-up-icon
							v-if="column.sortable"
							:class="{ 'arrow-down': filter.sort === 'ASC' }"
							class="inline-block vue-feather dark:text-gray-500 text-gray-300"
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

        <slot v-if="! isLoading && ! hasData" name="empty-page"></slot>

		<!--Paginator-->
        <div v-if="paginator && hasData" class="paginator-wrapper">

            <!--Show if there is only 6 pages-->
            <ul v-if="data.meta.total > 15 && data.meta.last_page <= 6" class="pagination flex align-items">

                <!--Go previous icon-->
                <li class="page-item previous">
                    <a @click="goToPage(pageIndex - 1)" class="page-link" :class="{ disabled: pageIndex == 0 }">
                        <chevron-left-icon size="14" class="icon" />
                    </a>
                </li>

                <li v-for="(page, index) in data.meta.last_page" :key="index" class="page-item" @click="goToPage(page)">
                    <a class="page-link" :class="{ active: pageIndex === page }">
                        {{ page }}
                    </a>
                </li>

				<!--Go next icon-->
                <li class="page-item next">
                    <a @click="goToPage(pageIndex + 1)" class="page-link" :class="{ disabled: pageIndex + 1 == data.meta.last_page }">
                        <chevron-right-icon size="14" class="icon" />
                    </a>
                </li>
            </ul>

			<!--Show if there is more than 6 pages-->
            <ul v-if="data.meta.total > 15 && data.meta.last_page > 6" class="pagination">

                <!--Go previous icon-->
                <li class="page-item previous">
                    <a @click="goToPage(pageIndex - 1)" class="page-link" :class="{ disabled: pageIndex == 0 }">
                        <chevron-left-icon size="14" class="icon" />
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
                        <chevron-right-icon size="14" class="icon" />
                    </a>
                </li>
            </ul>

            <span class="mt-10 flex items-center justify-between text-xs text-gray-600">
				Showing {{ data.meta.from }} - {{ data.meta.to }} from {{ data.meta.total }} records
			</span>
        </div>
    </div>
</template>

<script>
import {ChevronUpIcon, ChevronLeftIcon, ChevronRightIcon} from 'vue-feather-icons'
import DatatableCell from '/resources/js/components/Others/Tables/DatatableCell'
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
@import 'resources/sass/vuefilemanager/_variables';
@import 'resources/sass/vuefilemanager/_mixins';

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
</style>