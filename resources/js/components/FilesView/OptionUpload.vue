<template>
    <label class="menu-option group">
        <div class="icon-left group-hover-text-theme">
            <upload-cloud-icon v-if="type === 'file'" size="17" class="group-hover-text-theme"/>
            <folder-upload-icon v-if="type === 'folder'" size="17" class="group-hover-text-theme"/>
        </div>
        <div class="text-label group-hover-text-theme">
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
                @change="emmitFiles"
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
import FolderUploadIcon from "./Icons/FolderUploadIcon";
import {
    UploadCloudIcon,
} from 'vue-feather-icons'

    export default {
        name: 'Option',
        props:[
            'title',
			'type',
        ],
        components: {
            FolderUploadIcon,
            UploadCloudIcon,
        },
		methods: {
			emmitFiles(e) {
				this.$uploadFiles(e.target.files)
			}
		}
    }
</script>

<style scoped lang="scss">
@import "resources/sass/vuefilemanager/_variables";
@import "resources/sass/vuefilemanager/_mixins";

.menu-option {
    white-space: nowrap;
    font-weight: 700;
    @include font-size(14);
    padding: 15px 20px;
    cursor: pointer;
    width: 100%;
    color: $text;
    display: flex;
    align-items: center;

    .icon-left {
        margin-right: 20px;
        line-height: 0;

        path,
        line,
        polyline,
        rect,
        circle,
        polygon {
            color: inherit;
        }
    }

    .text-label {
        @include font-size(16);
    }

    &:hover {
        background: $light_background;
    }
}

.dark {

    .menu-option {
        color: $dark_mode_text_primary;

         &:hover {
            background: lighten($dark_mode_foreground, 2%);
        }
    } 
}

</style>
