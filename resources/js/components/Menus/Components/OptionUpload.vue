<template>
    <label
        class="flex items-center lg:py-3.5 py-4 px-5"
		:class="{'group cursor-pointer hover:bg-light-background dark:hover:bg-4x-dark-foreground': !isHoverDisabled }"
    >
        <div class="mr-4">
            <upload-cloud-icon v-if="type === 'file'" size="17" class="vue-feather group-hover-text-theme" />
            <folder-upload-icon v-if="type === 'folder'" size="17" class="vue-feather group-hover-text-theme" />
        </div>
        <div class="group-hover-text-theme text-left text-sm font-bold">
            {{ title }}

            <input
                v-if="type === 'file'"
                @change="emmitFiles"
                v-show="false"
                id="file"
                type="file"
                name="files[]"
                multiple
            />
            <input
                v-if="type === 'folder'"
                @change="emmitFolder"
                v-show="false"
                id="folder"
                type="file"
                name="folders[]"
                webkitdirectory
                mozdirectory
            />
        </div>
    </label>
</template>

<script>
import FolderUploadIcon from '../../Icons/FolderUploadIcon'
import { UploadCloudIcon } from 'vue-feather-icons'

export default {
    name: 'Option',
    props: ['title', 'type', 'isHoverDisabled'],
    components: {
        FolderUploadIcon,
        UploadCloudIcon,
    },
    methods: {
        emmitFiles(e) {
            this.$uploadFiles(e.target.files)
        },
        emmitFolder(e) {
            this.$store.commit('UPDATE_UPLOADING_FOLDER_STATE', true)

            this.$uploadFiles(e.target.files)
        },
    },
}
</script>
