<template>
    <div v-if="currentFile" class="select-none items-center px-3.5 py-4 lg:grid lg:grid-cols-3 lg:py-3">
        <div class="flex items-center justify-between lg:w-auto lg:justify-start">
            <!--Close icon-->
            <div @click="closeFullPreview" class="order-last -m-3 cursor-pointer p-3 lg:order-none">
                <x-icon size="16" class="vue-feather" />
            </div>

            <!--Item name-->
            <div class="flex items-center">
                <div class="mr-3 ml-0 flex items-center lg:mx-3">
                    <span
                        class="inline-block max-w-[150px] xs:max-w-[220px] md:max-w-[240px] overflow-hidden text-ellipsis whitespace-nowrap text-sm font-bold"
                    >
                        {{ currentFile.data.attributes.name }}
                    </span>

                    <span v-if="!fastPreview" class="ml-1 text-sm font-bold">
                        ({{ showingImageIndex + ' ' + $t('pronouns.of') + ' ' + files.length }})
                    </span>
                </div>

                <!--Context menu handler-->
                <PopoverWrapper>
                    <!--Icon-->
                    <span @click.stop="showItemContextMenu" class="-m-3 p-3">
                        <div
                            class="inline-block rounded-md bg-light-background py-0.5 px-1.5 align-middle transition-all duration-200 dark:bg-dark-foreground lg:bg-transparent"
                        >
                            <more-horizontal-icon size="14" />
                        </div>
                    </span>

                    <!--Desktop context menu-->
                    <PopoverItem name="file-preview-contextmenu" side="right">
                        <OptionGroup>
                            <Option
                                @click.native="$renameFileOrFolder(currentFile)"
                                :title="$t('rename')"
                                icon="rename"
                            />
                            <Option
                                @click.native="$moveFileOrFolder(currentFile)"
                                :title="$t('move')"
                                icon="move-item"
                            />
                            <Option
                                @click.native="$shareFileOrFolder(currentFile)"
                                :title="sharingTitle"
                                icon="share"
                                v-if="!$isThisRoute($route, ['Public', 'RequestUpload', 'SharedWithMe'])"
                            />
                            <Option
                                @click.native="$deleteFileOrFolder(currentFile)"
                                :title="$t('delete')"
                                icon="trash"
                                class="menu-option"
                            />
                        </OptionGroup>
                        <OptionGroup v-if="!$isThisRoute($route, ['RequestUpload'])">
                            <Option @click.native="downloadItem" :title="$t('download')" icon="download" />
                        </OptionGroup>
                    </PopoverItem>
                </PopoverWrapper>
            </div>
        </div>

        <!--Item info-->
        <small class="hidden text-center text-tiny font-normal text-gray-600 lg:block">
            {{ currentFile.data.attributes.filesize }}, {{ currentFile.data.attributes.created_at }}
        </small>

        <!--Actions-->
        <div class="hidden items-center lg:flex lg:justify-end">
            <div v-if="isPdf">
                <ToolbarButton @click.native="decreaseSizeOfPDF" source="zoom-out" :action="$t('pdf_zoom_out')" />
                <ToolbarButton @click.native="increaseSizeOfPDF" source="zoom-in" :action="$t('pdf_zoom_in')" />
            </div>

            <div class="ml-5">
                <ToolbarButton
                    v-if="!$isThisRoute($route, ['RequestUpload'])"
                    @click.native="downloadItem"
                    source="download"
                    :action="$t('download_item')"
                />
                <ToolbarButton
                    v-if="canShareItem"
                    @click.native="$shareFileOrFolder(currentFile)"
                    :class="{ 'is-inactive': !canShareItem }"
                    source="share"
                    :action="$t('share_item')"
                />
                <ToolbarButton
                    v-if="isImage"
                    @click.native="printMethod()"
                    source="print"
                    :action="$t('print')"
                />
            </div>
        </div>
    </div>
</template>

<script>
import PopoverWrapper from '../UI/Popover/PopoverWrapper'
import PopoverItem from '../UI/Popover/PopoverItem'
import OptionGroup from '../Menus/Components/OptionGroup'
import Option from '../Menus/Components/Option'

import ToolbarButton from '../UI/Buttons/ToolbarButton'
import { XIcon, MoreHorizontalIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'
import { events } from '../../bus'

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
        ...mapGetters(['fastPreview', 'clipboard', 'entries']),
        currentFile() {
            return this.fastPreview ? this.fastPreview : this.clipboard[0]
        },
        sharingTitle() {
            return this.currentFile.data.relationships.shared
                ? this.$t('edit_sharing')
                : this.$t('share')
        },
        isImage() {
            return this.currentFile.data.type === 'image'
        },
        isPdf() {
            return this.currentFile.data.attributes.mimetype === 'pdf'
        },
        files() {
            let files = []

            this.entries.map((element) => {
                if (this.currentFile.data.attributes.mimetype === 'pdf') {
                    if (element.data.attributes.mimetype === 'pdf') files.push(element)
                } else {
                    if (element.data.type === this.currentFile.data.type) files.push(element)
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
        },
    },
}
</script>
