<template>
	<div class="navigation-panel" v-if="currentFile">
		<div class="name-wrapper">

			<!--Close icon-->
			<span @click="closeFullPreview" class="p-3 -m-3">
				<x-icon size="17" class="icon-close hover-text-theme" />
			</span>

			<!--Item name-->
			<div class="name-count-wrapper">
				<p class="title">{{ currentFile.data.attributes.name }}</p>
				<span v-if="! fastPreview" class="file-count"> ({{ showingImageIndex + ' ' + $t('pronouns.of') + ' ' + files.length }}) </span>
			</div>

			<!--Context menu handler-->
			<PopoverWrapper>

				<!--Icon-->
				<span @click.stop="showItemContextMenu" class="p-3 -m-3">
					<div class="py-0.5 px-1.5 align-middle inline-block rounded-md lg:bg-transparent dark:bg-dark-foreground bg-light-background transition-all duration-200">
						<more-horizontal-icon size="14" />
					</div>
				</span>

				<!--Desktop context menu-->
				<PopoverItem name="file-preview-contextmenu" side="right">
					<OptionGroup>
						<Option @click.native="$renameFileOrFolder(currentFile)" :title="$t('context_menu.rename')" icon="rename" />
						<Option @click.native="$moveFileOrFolder(currentFile)" :title="$t('context_menu.move')" icon="move-item" />
						<Option @click.native="$shareFileOrFolder(currentFile)" :title="sharingTitle" icon="share" v-if="$checkPermission('master')" />
						<Option @click.native="$deleteFileOrFolder(currentFile)" :title="$t('context_menu.delete')" icon="trash" class="menu-option" />
					</OptionGroup>
					<OptionGroup>
						<Option @click.native="downloadItem" :title="$t('context_menu.download')" icon="download" />
					</OptionGroup>
				</PopoverItem>
			</PopoverWrapper>
		</div>

		<!--Item metadata-->
		<div class="created-at-wrapper">
			<p>{{ currentFile.data.attributes.filesize }}, {{ currentFile.data.attributes.created_at }}</p>
		</div>

		<!--Icon actions-->
		<div class="navigation-icons">
			<div v-if="isPdf" class="navigation-tool-wrapper">
				<ToolbarButton @click.native="decreaseSizeOfPDF" source="zoom-out" :action="$t('pdf_zoom_out')" />
				<ToolbarButton @click.native="increaseSizeOfPDF" source="zoom-in" :action="$t('pdf_zoom_in')" />
			</div>
			<div class="navigation-tool-wrapper">
				<ToolbarButton @click.native="downloadItem" class="mobile-hide" source="download" :action="$t('actions.download')" />
				<ToolbarButton v-if="canShareItem" @click.native="$shareFileOrFolder(currentFile)" class="mobile-hide" :class="{ 'is-inactive': !canShareItem }" source="share" :action="$t('actions.share')" />
				<ToolbarButton v-if="isImage" @click.native="printMethod()" source="print" :action="$t('actions.print')" />
			</div>
		</div>
	</div>
</template>

<script>
	import PopoverWrapper from "../Desktop/PopoverWrapper";
	import PopoverItem from "../Desktop/PopoverItem";
	import OptionGroup from "../FilesView/OptionGroup";
	import Option from "../FilesView/Option";

    import ToolbarButton from "../FilesView/ToolbarButton";
    import {XIcon, MoreHorizontalIcon} from 'vue-feather-icons'
    import {mapGetters} from 'vuex'
    import {events} from "../../bus";

    export default {
        name: 'FilePreviewToolbar',
        components: {
            MoreHorizontalIcon,
			PopoverWrapper,
            ToolbarButton,
			PopoverItem,
			OptionGroup,
			Option,
            XIcon,
        },
        computed: {
            ...mapGetters([
                'fastPreview',
                'clipboard',
                'entries'
            ]),
			currentFile() {
				return this.fastPreview ? this.fastPreview : this.clipboard[0]
			},
			sharingTitle() {
				return this.currentFile.data.relationships.shared
					? this.$t('context_menu.share_edit')
					: this.$t('context_menu.share')
			},
            isImage() {
                return this.currentFile.data.type === 'image'
            },
            isPdf() {
                return this.currentFile.data.attributes.mimetype === 'pdf'
            },
            files() {
                let files = []

                this.entries.map(element => {

                    if (this.currentFile.data.attributes.mimetype === 'pdf') {

                        if (element.data.attributes.mimetype === 'pdf')
                            files.push(element)

                    } else {

                        if (element.data.type === this.currentFile.data.type)
                            files.push(element)
                    }
                })

                return files
            },
            showingImageIndex() {
                let activeIndex = undefined

                this.files.forEach((element, index) => {
                    if (element.data.id === this.currentFile.data.id) {
                        activeIndex = index + 1
                    }
                })

                return activeIndex
            },
            canShareItem() {
				return this.$isThisRoute(this.$route, ['Files', 'RecentUploads', 'MySharedItems'])
            },
        },
        methods: {
			showItemContextMenu() {
				if (this.$isMobile()) {
					events.$emit('mobile-context-menu:show', this.currentFile)
					this.$showMobileMenu('file-menu')
				} else {
					events.$emit('popover:open', 'file-preview-contextmenu')
				}
			},
            increaseSizeOfPDF() {
                events.$emit('document-zoom:in')
            },
            decreaseSizeOfPDF() {
                events.$emit('document-zoom:out')
            },
            printMethod() {
                let tab = document.getElementById('printable-file')
                let win = window.open('', '', 'height=700,width=700')

                win.document.write(tab.outerHTML)
                win.document.close()
                win.print()
            },
            downloadItem() {
                this.$downloadFile(
                    this.currentFile.data.attributes.file_url,
                    this.currentFile.data.attributes.name + '.' + this.currentFile.data.attributes.mimetype
                )
            },
            closeFullPreview() {
                events.$emit('file-preview:hide')
            }
        }
    }
</script>

<style lang="scss" scoped>
@import '/resources/sass/vuefilemanager/_variables';
@import '/resources/sass/vuefilemanager/_mixins';

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
            .title {
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
        border-radius: 6px;
        vertical-align: middle;
        cursor: pointer;
        @include transition(150ms);

        &:hover {
            background: $light_background;

            line {
                color: inherit;
            }
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

.dark {
    .navigation-panel {
        background-color: $dark_mode_background;
        color: $dark_mode_text_primary;

        .icon-close {
            color: $dark_mode_text_primary;

            &:hover {
                background-color: $dark_mode_background;
            }
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