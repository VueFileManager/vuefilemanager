<template>
	<div class="navigation-panel" v-if="clipboard[0]">
		<div class="name-wrapper">
			<x-icon @click="closeFullPreview" size="22" class="icon-close hover-text-theme" />
			<div class="name-count-wrapper">
				<p class="title">{{ clipboard[0].name }}</p>
				<span class="file-count"> ({{ showingImageIndex + ' ' + $t('pronouns.of') + ' ' + files.length }}) </span>
			</div>
			<span @click.stop="menuOpen" id="fast-preview-menu" class="fast-menu-icon group">
				<more-horizontal-icon class="more-icon group-hover-text-theme" size="14" />
			</span>
		</div>

		<div class="created-at-wrapper">
			<p>{{ clipboard[0].filesize }}, {{ clipboard[0].created_at }}</p>
		</div>

		<div class="navigation-icons">
			<div v-if="isPdf" class="navigation-tool-wrapper">
				<ToolbarButton @click.native="increaseSizeOfPDF" source="zoom-in" :action="$t('pdf_zoom_in')" />
				<ToolbarButton @click.native="decreaseSizeOfPDF" source="zoom-out" :action="$t('pdf_zoom_out')" />
			</div>
			<div class="navigation-tool-wrapper">
				<ToolbarButton @click.native="downloadItem" class="mobile-hide" source="download" :action="$t('actions.download')" />
				<ToolbarButton v-if="canShareItem" @click.native="shareItem" class="mobile-hide" :class="{ 'is-inactive': !canShareItem }" source="share" :action="$t('actions.share')" />
				<ToolbarButton v-if="isImage" @click.native="printMethod()" source="print" :action="$t('actions.print')" />
			</div>
		</div>
	</div>
</template>

<script>
    import ToolbarButton from '@/components/FilesView/ToolbarButton'
    import {XIcon, MoreHorizontalIcon} from 'vue-feather-icons'
    import {mapGetters} from 'vuex'
    import {events} from '@/bus'

    export default {
        name: 'FilePreviewToolbar',
        components: {
            MoreHorizontalIcon,
            ToolbarButton,
            XIcon,
        },
        computed: {
            ...mapGetters([
                'clipboard',
                'data'
            ]),
            isImage() {
                return this.clipboard[0].type === 'image'
            },
            isPdf() {
                return this.clipboard[0].mimetype === 'pdf'
            },
            files() {
                let files = []

                this.data.map(element => {

                    if (this.clipboard[0].mimetype === 'pdf') {

                        if (element.mimetype === 'pdf')
                            files.push(element)

                    } else {

                        if (element.type === this.clipboard[0].type)
                            files.push(element)
                    }
                })

                return files
            },
            showingImageIndex() {
                let activeIndex = undefined

                this.files.forEach((element, index) => {
                    if (element.id === this.clipboard[0].id) {
                        activeIndex = index + 1
                    }
                })

                return activeIndex
            },
            canShareItem() {
                return this.$isThisLocation([
                    'base', 'participant_uploads', 'latest', 'shared'
                ])
            },
        },
        methods: {
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
                    this.clipboard[0].file_url,
                    this.clipboard[0].name + '.' + this.clipboard[0].mimetype
                )
            },
            shareItem() {
                let event = this.clipboard[0].shared
                    ? 'share-edit'
                    : 'share-create'

                events.$emit('popup:open', {
                    name: event,
                    item: this.clipboard[0]
                })
            },
            menuOpen() {
                if (this.$isMobile()) {
                    events.$emit('mobile-menu:show', 'file-menu')
                } else {
                    events.$emit('showContextMenuPreview:show', this.clipboard[0])
                }
            },
            closeFullPreview() {
                events.$emit('file-preview:hide')
                events.$emit('showContextMenuPreview:hide')
            }
        }
    }
</script>

<style lang="scss" scoped>
@import '@assets/vuefilemanager/_variables';
@import '@assets/vuefilemanager/_mixins';

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
        padding: 1px 4px;
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
                color: inherit;
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