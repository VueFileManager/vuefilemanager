<template>
    <div id="desktop-toolbar">
        <div class="toolbar-wrapper">

			<div class="location">
				<!--<chevron-left-icon :class="{'is-active': browseHistory.length > 1 }" class="icon-back" size="17" />-->

				<span class="location-title">
					Invoices
				</span>
			</div>

			<ToolbarWrapper>

				<!--Search bar-->
				<ToolbarGroup style="margin-left: 0">
					<SearchBar v-model="query" @reset-query="query = ''" placeholder="Search your invoices..." />
				</ToolbarGroup>

				<!--Creating controls-->
				<ToolbarGroup>
					<PopoverWrapper>
                    	<ToolbarButton @click.stop.native="createCreateMenu" source="file-plus" :action="$t('actions.create_folder')" />
						<PopoverItem name="desktop-create-invoices">
							<OptionGroup>
								<Option title="Create Invoice" icon="file-text" />
								<Option title="Create Advance Invoice" icon="clock" />
							</OptionGroup>
							<OptionGroup>
								<Option title="Create Client" icon="user-plus" />
							</OptionGroup>
						</PopoverItem>
					</PopoverWrapper>
				</ToolbarGroup>

				<!--Invoice Controls-->
				<ToolbarGroup v-if="! $isMobile()">
                    <ToolbarButton @click.native="shareInvoice" :class="{'is-inactive': canActiveInView }" source="send" :action="$t('actions.share')" />
                    <ToolbarButton @click.native="shareInvoice" :class="{'is-inactive': canActiveInView }" source="rename" :action="$t('actions.share')" />
                    <ToolbarButton @click.native="deleteInvoice" :class="{'is-inactive': canActiveInView }" source="trash" :action="$t('actions.delete')" />
				</ToolbarGroup>

				<!--View Controls-->
				<ToolbarGroup>
					<PopoverWrapper>
						<ToolbarButton @click.stop.native="showSortingMenu" source="preview-sorting" :action="$t('actions.sorting_view')" />
						<PopoverItem name="desktop-sorting">
							<OptionGroup>
								<Option @click.native.stop="sort('created_at')" :title="$t('preview_sorting.sort_date')" icon="calendar" />
								<Option @click.native.stop="sort('name')" :title="$t('preview_sorting.sort_alphabet')" icon="alphabet" />
							</OptionGroup>
						</PopoverItem>
					</PopoverWrapper>
                    <ToolbarButton @click.native="$store.dispatch('fileInfoToggle')" :class="{'active': isVisibleSidebar }" :action="$t('actions.info_panel')" source="info" />
				</ToolbarGroup>
			</ToolbarWrapper>
        </div>
    </div>
</template>

<script>
	import FileSortingOptions from '@/components/FilesView/FileSortingOptions'
	import {ChevronLeftIcon, MoreHorizontalIcon} from 'vue-feather-icons'
	import PopoverWrapper from '@/components/Desktop/PopoverWrapper'
	import ToolbarWrapper from '@/components/Desktop/ToolbarWrapper'
	import ToolbarButton from '@/components/FilesView/ToolbarButton'
	import ToolbarGroup from '@/components/Desktop/ToolbarGroup'
	import PopoverItem from '@/components/Desktop/PopoverItem'
	import SearchBar from '@/components/FilesView/SearchBar'
	import {mapGetters} from 'vuex'
	import {events} from '@/bus'
	import OptionGroup from '@/components/FilesView/OptionGroup'
	import Option from '@/components/FilesView/Option'

	export default {
		name: 'InvoiceDesktopToolbar',
		components: {
			FileSortingOptions,
			MoreHorizontalIcon,
			ChevronLeftIcon,
			ToolbarWrapper,
			PopoverWrapper,
			ToolbarButton,
			ToolbarGroup,
			PopoverItem,
			SearchBar,
			OptionGroup,
			Option,
		},
		computed: {
			...mapGetters([
				'isVisibleSidebar',
				'clipboard',
			]),
			canActiveInView() {
				let locations = [
					'invoices',
					'advance-invoices',
				]
				return !this.$isThisLocation(locations) || this.clipboard.length === 0
			},
		},
		data() {
			return {
				query: '',
			}
		},
		watch: {
			query(val) {
				this.$searchFiles(val)
			}
		},
		methods: {
			showSortingMenu() {
				events.$emit('popover:open', 'desktop-sorting')
			},
			createCreateMenu() {
				events.$emit('popover:open', 'desktop-create-invoices')
			},
			deleteInvoice() {
				if (this.clipboard.length > 0)
					this.$store.dispatch('deleteInvoice')
			},
			shareInvoice() {
				alert('Send Invoice')
			},
		},
	}
</script>

<style scoped lang="scss">
@import "@assets/vuefilemanager/_variables";
@import "@assets/vuefilemanager/_mixins";

.is-inactive {
	opacity: 0.25;
	pointer-events: none;
}

.toolbar-wrapper {
	padding-top: 10px;
	padding-bottom: 10px;
	display: flex;
	justify-content: space-between;
	align-items: center;
	position: relative;
	z-index: 2;
}

.location {
	align-items: center;
	cursor: pointer;
	display: flex;

	.icon-back {
		@include transition(150ms);
		pointer-events: none;
		margin-right: 6px;
		flex-shrink: 0;
		opacity: 0.15;

		&.is-active {
			opacity: 1;
			pointer-events: initial;
		}
	}

	.location-title {
		@include font-size(15);
		line-height: 1;
		font-weight: 700;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		color: $text;
	}

	.location-more {
		margin-left: 6px;
		padding: 1px 4px;
		line-height: 0;
		border-radius: 3px;
		@include transition(150ms);

		svg circle {
			@include transition(150ms);
		}

		&:hover {
			background: $light_background;

			svg circle {
				color: inherit;
			}
		}
	}
}

.toolbar-position {
	text-align: center;

	span {
		@include font-size(17);
		font-weight: 600;
	}
}

@media only screen and (max-width: 1024px) {
	.location {

		.location-title {
			max-width: 120px;
		}
	}

	.toolbar-tools {
		.button {
			margin-left: 0;
			height: 40px;
			width: 40px;
		}
	}
}

@media only screen and (max-width: 960px) {
	#desktop-toolbar {
		display: none;
	}
}

@media (prefers-color-scheme: dark) {
	.toolbar .directory-name {
		color: $dark_mode_text_primary;
	}

	.toolbar-go-back {
		.location-title {
			color: $dark_mode_text_primary;
		}

		.location-more {
			&:hover {
				background: $dark_mode_foreground;
			}
		}
	}
}
</style>
