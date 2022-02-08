<template>
    <div class="hidden 2xl:w-[360px] w-[320px] shrink-0 overflow-y-auto overflow-x-hidden px-2.5 pt-2 lg:block">
        <!--Is empty clipboard-->
        <div v-if="isEmpty" class="flex h-full items-center justify-center">
            <div class="text-center">
                <eye-off-icon size="22" class="vue-feather mb-3 inline-block text-gray-500" />
                <small class="block text-xs text-gray-500">
                    {{ $t('messages.nothing_to_preview') }}
                </small>
            </div>
        </div>

        <!--Multiple item selection-->
        <TitlePreview
            v-if="!isSingleFile && !isEmpty"
            class="mb-6"
            icon="check-square"
            :title="$t('file_detail.selected_multiple')"
            :subtitle="this.clipboard.length + ' ' + $tc('file_detail.items', this.clipboard.length)"
        />

        <!--Single file preview-->
        <div v-if="isSingleFile && !isEmpty">
            <FilePreviewDetail />

            <TitlePreview class="mb-6" :icon="clipboard[0].data.type" :title="clipboard[0].data.attributes.name" :subtitle="clipboard[0].data.attributes.mimetype" />

            <!--Filesize-->
            <ListInfoItem v-if="singleFile.data.attributes.filesize" :title="$t('file_detail.size')" :content="singleFile.data.attributes.filesize" />

            <!--Created At-->
            <ListInfoItem :title="$t('file_detail.created_at')" :content="singleFile.data.attributes.created_at" />

            <!--Location-->
            <ListInfoItem v-if="$checkPermission(['master'])" :title="$t('file_detail.where')">
                <div @click="$moveFileOrFolder(singleFile)" class="flex cursor-pointer items-center">
                    <b class="inline-block text-sm font-bold">
                        {{ singleFile.data.relationships.parent ? singleFile.data.relationships.parent.data.attributes.name : $getCurrentLocationName() }}
                    </b>
                    <Edit2Icon size="10" class="ml-2" />
                </div>
            </ListInfoItem>

            <!--Shared-->
            <ListInfoItem v-if="$checkPermission('master') && singleFile.data.relationships.shared" :title="$t('file_detail.shared')">
                <div @click="$shareFileOrFolder(singleFile)" class="mb-2 flex cursor-pointer items-center">
                    <span class="inline-block text-sm font-bold">
                        {{ sharedInfo }}
                    </span>
                    <Edit2Icon size="10" class="ml-2" />
                </div>
                <div class="flex w-full items-center">
                    <lock-icon v-if="isLocked" @click="$shareFileOrFolder(singleFile)" size="17" class="hover-text-theme vue-feather cursor-pointer" />
                    <unlock-icon v-if="!isLocked" @click="$shareFileOrFolder(singleFile)" size="17" class="hover-text-theme vue-feather cursor-pointer" />
                    <CopyShareLink :item="singleFile" size="small" class="w-full pl-2.5" />
                </div>
            </ListInfoItem>

            <!--Author-->
            <ListInfoItem v-if="canShowAuthor" :title="$t('Author')">
                <div class="mt-1.5 flex items-center">
                    <MemberAvatar :size="32" :member="singleFile.data.relationships.owner" />
                    <span class="text-sm ml-3 block font-bold">
                        {{ singleFile.data.relationships.owner.data.attributes.name }}
                    </span>
                </div>
            </ListInfoItem>

            <!--Metadata-->
            <ListInfoItem v-if="canShowMetaData" :title="$t('file_detail_meta.meta_data')">
                <ImageMetaData />
            </ListInfoItem>
        </div>
    </div>
</template>

<script>
import FilePreviewDetail from '../Others/FilePreviewDetail'
import CopyShareLink from '../Others/Forms/CopyShareLink'
import { Edit2Icon, LockIcon, UnlockIcon, EyeOffIcon } from 'vue-feather-icons'
import ImageMetaData from './ImageMetaData'
import TitlePreview from './TitlePreview'
import TeamMembersPreview from '../Teams/Components/TeamMembersPreview'
import ListInfoItem from '../Others/ListInfoItem'
import MemberAvatar from './MemberAvatar'
import { mapGetters } from 'vuex'

export default {
    name: 'InfoSidebar',
    components: {
        TeamMembersPreview,
        FilePreviewDetail,
        ImageMetaData,
        CopyShareLink,
        MemberAvatar,
        TitlePreview,
        ListInfoItem,
        UnlockIcon,
        EyeOffIcon,
        Edit2Icon,
        LockIcon,
    },
    computed: {
        ...mapGetters(['permissionOptions', 'clipboard', 'user']),
        isEmpty() {
            return this.clipboard.length === 0
        },
        isSingleFile() {
            return this.clipboard.length === 1
        },
        singleFile() {
            return this.clipboard[0]
        },
        canShowMetaData() {
            return this.clipboard[0].data.attributes.metadata && this.clipboard[0].data.attributes.metadata.ExifImageWidth
        },
        isLocked() {
            return this.clipboard[0].data.relationships.shared.protected
        },
        sharedInfo() {
            let title = this.permissionOptions.find((option) => {
                return option.value === this.clipboard[0].data.relationships.shared.permission
            })

            return title ? this.$t(title.label) : this.$t('shared.can_download')
        },
        canShowAuthor() {
            return (
                this.$isThisRoute(this.$route, ['SharedWithMe', 'TeamFolders']) &&
                this.clipboard[0].data.type !== 'folder' &&
                this.user.data.id !== this.clipboard[0].data.relationships.owner.data.id
            )
        },
    },
}
</script>
