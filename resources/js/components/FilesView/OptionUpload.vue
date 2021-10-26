<template>
    <label for="file" class="menu-option group">
        <div class="icon-left group-hover-text-theme">
            <upload-cloud-icon size="17" class="group-hover-text-theme"/>
        </div>
        <div class="text-label group-hover-text-theme">
            {{ title }}
			<input
				@change="emmitFiles"
				v-show="false"
				id="file"
				type="file"
				name="files[]"
				multiple
			/>
        </div>
    </label>
</template>

<script>
import {events} from '/resources/js/bus'
import {
    UploadCloudIcon,
} from 'vue-feather-icons'

    export default {
        name: 'Option',
        props:[
            'title',
        ],
        components: {
            UploadCloudIcon,
        },
		methods: {
			emmitFiles(e) {
				this.$uploadFiles(e.target.files)
				events.$emit('popover:close', 'desktop-create')
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
}

.dark {

    .menu-option {
        color: $dark_mode_text_primary;
    } 
}

</style>
