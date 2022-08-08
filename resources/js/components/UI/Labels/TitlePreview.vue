<template>
    <div class="flex items-start">
        <div class="mr-2">
            <CheckSquareIcon v-if="icon === 'check-square'" class="text-theme vue-feather" size="19" />
            <image-icon v-if="icon === 'image'" class="text-theme vue-feather" size="19" />
            <video-icon v-if="icon === 'video'" class="text-theme vue-feather" size="19" />
            <folder-icon v-if="icon === 'folder'" class="text-theme vue-feather" size="19" />
            <file-icon v-if="icon === 'file'" class="text-theme vue-feather" size="19" />
        </div>
        <div>
            <b class="block overflow-hidden text-ellipsis text-sm font-bold">
                {{ title }}
            </b>
            <small class="block text-xs font-bold text-gray-400">
                {{ subtitle }}
            </small>
        </div>
    </div>
</template>

<script>
import { CheckSquareIcon, FolderIcon, ImageIcon, VideoIcon, FileIcon } from 'vue-feather-icons'

export default {
    name: 'TitlePreview',
    props: ['subtitle', 'title', 'icon'],
    components: {
        CheckSquareIcon,
        FolderIcon,
        ImageIcon,
        VideoIcon,
        FileIcon,
    },
}
</script>
