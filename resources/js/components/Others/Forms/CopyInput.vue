<template>
    <div class="inline-wrapper icon-append copy-input" :class="size" @click="copyUrl">
        <input ref="sel" :value="str" id="link-input" type="text" class="input-text" readonly>
        <div class="multi-icon">
            <div class="icon-item group hover-bg-theme-100">
                <copy-icon v-if="! isCopiedLink" size="14" class="group-hover-text-theme hover-text-theme"/>
                <check-icon v-if="isCopiedLink" size="14" class="group-hover-text-theme hover-text-theme"/>
            </div>
        </div>
    </div>
</template>

<script>
import { CopyIcon, CheckIcon, SendIcon } from 'vue-feather-icons'

export default {
    name: 'CopyInput',
    props: [
		'size',
		'str'
	],
    components: {
        CheckIcon,
        CopyIcon,
        SendIcon
    },
    data() {
        return {
            isCopiedLink: false
        }
    },
    methods: {
        copyUrl() {

            // Get input value
            var copyText = document.getElementById('link-input')

            // select link
            copyText.select()
            copyText.setSelectionRange(0, 99999)

            // Copy
            document.execCommand('copy')

            // Mark button as copied
            this.isCopiedLink = true

            // Reset copy button
            setTimeout(() => {
                this.isCopiedLink = false
            }, 1000)
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@assets/vuefilemanager/_variables';
@import '@assets/vuefilemanager/_mixins';
@import "@assets/vuefilemanager/_inapp-forms.scss";
@import "@assets/vuefilemanager/_forms.scss";

.multi-icon {
    display: flex;
    align-items: center;
    background: $light_background;
    border-bottom-right-radius: 8px;
    border-top-right-radius: 8px;

    line,
	rect,
    path,
    polygon {
        color: $text;
    }

    .icon-item {
        padding: 9px 10px;
        display: flex;
        align-items: center;
        border-left: 1px solid $light_mode_border_darken;
        cursor: pointer;

        &:hover {

            line,
            polyline,
            path,
			rect,
            polygon {
                color: inherit;
            }
        }

        &:first-child {
            border-left: none;
        }

        &:last-child {
            border-bottom-right-radius: 8px;
            border-top-right-radius: 8px;
        }
    }


}

// Single page
.copy-input {
    border: 1px solid $light_mode_border_darken;
    border-radius: 8px;

    &.small {

        &.icon-append {

            .icon {
                padding: 10px;
            }
        }

        input {
            padding: 6px 10px;
            @include font-size(13);
        }
    }

    .icon {
        cursor: pointer;
    }

    input {
        text-overflow: ellipsis;
        box-shadow: none;

        &:disabled {
            color: $text;
            cursor: pointer;
        }
    }
}

.dark-mode {

    .copy-input {
        border-color: #333333;
    }

    .multi-icon {
        background: $dark_mode_foreground;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);

        line,
        path,
		rect,
        polygon {
            color: inherit !important;
        }

        .icon-item {
            border-color: #333333;
        }
    }

    .copy-input {
        input {
            color: $dark_mode_text_primary;
        }
    }
}
</style>
