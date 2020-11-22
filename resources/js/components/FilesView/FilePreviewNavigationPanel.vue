<template>
	<div class="navigation-panel" v-if="fileInfoDetail[0]">
		<div class="name-wrapper">
			<x-icon @click="closeFullPreview" size="22" class="icon-close"></x-icon>
			<div class="name-count-wrapper">
				<p class="title">{{ fileInfoDetail[0].name }}</p>				
				<span class="file-count"> ({{ showingImageIndex + ' ' + $t('pronouns.of') + ' ' + filteredFiles.length }}) </span>
			</div>
			<span id="fast-preview-menu" class="fast-menu-icon" @click="menuOpen" v-if="$checkPermission(['master', 'editor'])">
				<more-horizontal-icon class="more-icon" size="14"> </more-horizontal-icon>
			</span>
		</div>

		<div class="created-at-wrapper">
			<p>{{ fileInfoDetail[0].filesize }}, {{ fileInfoDetail[0].created_at }}</p>
		</div>
		<div class="navigation-icons">
			<div class="navigation-tool-wrapper">
				<ToolbarButton source="download" class="mobile-hide" @click.native="downloadItem" :action="$t('actions.download')" />
				<ToolbarButton v-if="canShowShareView" :class="{ 'is-inactive': canShareInView }" @click.native="shareItem" source="share" class="mobile-hide" :action="$t('actions.share')" />
				<ToolbarButton v-if="this.fileInfoDetail[0].type === 'image'" source="print" :action="$t('actions.print')" @click.native="printMethod()" />
			</div>
		</div>
	</div>
</template>

<script>
import { events } from '@/bus'
import { mapGetters } from 'vuex'
import { XIcon, MoreHorizontalIcon } from 'vue-feather-icons'

import ToolbarButton from '@/components/FilesView/ToolbarButton'

export default {
	name: 'FilePreviewNavigationPanel',
	components: { ToolbarButton, XIcon, MoreHorizontalIcon },
	computed: {
		...mapGetters(['fileInfoDetail', 'data']),
		filteredFiles() {
			let files = []
			this.data.filter((element) => {
				if (element.type == this.fileInfoDetail[0].type) {
					files.push(element)
				}
			})
			return files
		},
		showingImageIndex() {
			let activeIndex = ''
			this.filteredFiles.filter((element, index) => {
				if (element.unique_id == this.fileInfoDetail[0].unique_id) {
					activeIndex = index + 1
				}
			})
			return activeIndex
		},
        canShowShareView() {
			return this.$isThisLocation(['base', 'participant_uploads', 'latest', 'shared'])
        },
		canShareInView() {
			return ! this.$isThisLocation(['base', 'participant_uploads', 'latest', 'shared'])
		}
	},
	data() {
		return {
			showContextMenu: false
		}
	},
	methods: {
		printMethod() {
			var tab = document.getElementById('image')
			var win = window.open('', '', 'height=700,width=700')
			win.document.write(tab.outerHTML)
			win.document.close()
			win.print()
		},
		downloadItem() {
			// Download file
			this.$downloadFile(this.fileInfoDetail[0].file_url, this.fileInfoDetail[0].name + '.' + this.fileInfoDetail[0].mimetype)
		},
		shareItem() {
			if (this.fileInfoDetail[0].shared) {
				events.$emit('popup:open', {
					name: 'share-edit',
					item: this.fileInfoDetail[0]
				})
			} else {
				events.$emit('popup:open', {
					name: 'share-create',
					item: this.fileInfoDetail[0]
				})
			}
		},
		menuOpen() {
			if (this.$isMobile()) {
				events.$emit('mobileMenu:show', 'showFromMediaPreview')
			} else {
				events.$emit('showContextMenuPreview:show', this.fileInfoDetail[0])
			}
		},
		closeFullPreview() {
			events.$emit('fileFullPreview:hide')
			events.$emit('showContextMenuPreview:hide')
		}
	}
}
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

.name-wrapper {
	width: 33%;
	height: 22px;
	display: flex;
	position: relative;
	align-items: center;
	flex-grow: 1;
	align-self: center;
	white-space: nowrap;

	.name-count-wrapper {
        margin-left: 6px;
        margin-right: 6px;

        .file-count {
			@include font-size(15);
			line-height: 1;
			font-weight: 700;
			overflow: hidden;
			text-overflow: ellipsis;
			display: inline-block;
			vertical-align: middle;
			align-self: center;
			color: $text;
		}
		.title {
			@include font-size(15);
			max-width: 250px;
			line-height: 1;
			font-weight: 700;
			overflow: hidden;
			text-overflow: ellipsis;
			display: inline-block;
			vertical-align: middle;
			color: $text;
		}
		@media (max-width: 570px) {
			.title{
				max-width: 180px;
				@include font-size(17);
			}
			.file-count {
				@include font-size(17);
			}
		}
	}
	.icon-close {
		min-width: 22px;
		padding: 1px 4px;
		border-radius: 6px;
		vertical-align: middle;
		cursor: pointer;
		color: $text;
		@include transition(150ms);

		&:hover {
			background: $light_background;

			line {
				stroke: $theme;
			}
		}
	}
	.fast-menu-icon {
		height: 24px;
		display: flex;
		align-items: center;
		vertical-align: middle;
		padding: 1px 4px;
		line-height: 0;
		border-radius: 3px;
		cursor: pointer;
		@include transition(150ms);

		svg circle {
			@include transition(150ms);
		}
		&:hover {
			background: $light_background;

			svg circle {
				stroke: $theme;
			}
		}
		.more-icon {
			vertical-align: middle;
			cursor: pointer;
		}
	}
}
.context-menu {
	min-width: 250px;
	position: absolute;
	z-index: 99;
	box-shadow: $shadow;
	background: white;
	border-radius: 8px;
	overflow: hidden;
	top: 29px;

	&.showed {
		display: block;
	}
}

.created-at-wrapper {
	width: 33%;
	display: flex;
	text-align: center;
	justify-content: center;

	p {
		display: flex;
		align-items: center;
		@include font-size(11);
	}
}

.navigation-icons {
	width: 33%;
	text-align: right;

	.navigation-tool-wrapper {
		margin-left: 28px;
		display: inline-block;
		vertical-align: middle;
	}

	.button {
		margin-left: 5px;
		&:hover {
			background: $light_background;
		}
	}
}

.navigation-panel {
	height: 63px;
	width: 100%;
	padding: 10px 15px;
	display: flex;
	position: absolute;
	z-index: 8;
	align-items: center;
	background-color: white;
	color: $text;
}

@media (max-width: 960px) {

    .context-menu {

        .name-wrapper {
            width: 67%;
        }
    }

    .navigation-icons {
        display: none;
    }

    .navigation-panel {
        height: 53px;
        padding: 15px;
    }

    .created-at-wrapper {
        display: none;
    }

    .name-wrapper {
        justify-content: space-between;
        flex-direction: row-reverse;
        width: 100%;
    }
}

@media (prefers-color-scheme: dark) {
	.navigation-panel {
		background-color: $dark_mode_background;
		color: $dark_mode_text_primary;

		.icon-close {
			color: $dark_mode_text_primary;
			&:hover {
				background-color: $dark_mode_background;
			}
		}

		.fast-menu-icon:hover {
			background: $dark_mode_background;
		}
	}

	.name-wrapper {
		.title,
		.file-count {
			color: $dark_mode_text_primary !important;
		}
	}

	.navigation-icons {
		.button:hover {
			background: $dark_mode_background;
		}
	}
}
</style>