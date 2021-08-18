<template>
    <div class="inline-wrapper icon-append copy-input" :class="size">
        <input ref="sel" :value="item.shared.link" id="link-input" type="text" class="input-text" readonly>
        <div class="multi-icon">
            <div @click="copyUrl" class="icon-item group hover-bg-theme-100">
                <link-icon v-if="! isCopiedLink" size="14" class="group-hover-text-theme hover-text-theme"/>
                <check-icon v-if="isCopiedLink" size="14" class="group-hover-text-theme hover-text-theme"/>
            </div>
            <div @click.stop.prevent="moreOptions" class="icon-item group hover-bg-theme-100">
                <more-horizontal-icon size="14" class="group-hover-text-theme hover-text-theme" />
            </div>
        </div>

		<ul v-if="isOpenedMoreOptions" class="input-options">
			<li class="option-item" @click="sendOnEmail">
				<div class="option-icon">
					<send-icon size="14" />
				</div>
				<span class="option-value">{{ $t('sharelink.share_via_email') }}</span>
			</li>
			<li class="option-item" @click="copyIframe">
				<div class="option-icon">
					<code-icon size="14" />
				</div>
				<span class="option-value">{{ $t('sharelink.copy_embed') }}</span>
			</li>
		</ul>

		<textarea v-model="iframeCode" ref="iframe" class="iframe-output"></textarea>
    </div>
</template>

<script>
import { LinkIcon, CheckIcon, SendIcon, MoreHorizontalIcon, CodeIcon } from 'vue-feather-icons'
import { events } from '/resources/js/bus'

export default {
    name: 'CopyShareLink',
    props: [
		'size',
		'item',
	],
    components: {
		MoreHorizontalIcon,
        CheckIcon,
		CodeIcon,
        LinkIcon,
        SendIcon
    },
    data() {
        return {
			iframeCode: '',
            isCopiedLink: false,
			isOpenedMoreOptions: false,
        }
    },
    methods: {
        moreOptions() {
            this.isOpenedMoreOptions = ! this.isOpenedMoreOptions
        },
		sendOnEmail() {
            events.$emit('popup:open', {
                name: 'share-edit',
                item: this.item,
                sentToEmail: true,
            })

			this.isOpenedMoreOptions = false
        },
		copyIframe() {
        	// generate iframe
        	this.iframeCode = `<iframe src="${this.item.shared.link}" width="790" height="400" allowfullscreen frameborder="0"></iframe>`

			let copyText = this.$refs.iframe

			copyText.select()
			copyText.setSelectionRange(0, 99999)

			document.execCommand('copy')

			events.$emit('toaster', {
				type: 'success',
				message: this.$t('Your web insert code was copied'),
			})

			this.isOpenedMoreOptions = false
        },
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
@import '/resources/sass/vuefilemanager/_variables';
@import '/resources/sass/vuefilemanager/_mixins';
@import "resources/sass/vuefilemanager/_inapp-forms.scss";
@import "resources/sass/vuefilemanager/_forms.scss";

.input-options {
	box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
	background: white;
	border-radius: 8px;
	position: absolute;
	overflow: hidden;
	top: 45px;
	left: 0;
	right: 0;
	z-index: 9;
	max-height: 295px;
	overflow-y: auto;

	.option-item {
		padding: 13px 20px;
		display: block;
		cursor: pointer;

		&:hover {
			color: $theme;
			background: $light_background;
		}

		&:last-child {
			border-bottom: none;
		}
	}

	.option-icon {
		width: 20px;
		display: inline-block;
		@include font-size(10);

		svg {
			margin-top: -4px;
			vertical-align: middle;
		}
	}

	.option-value {
		@include font-size(14);
		font-weight: 700;
		width: 100%;
		vertical-align: middle;

		&.placehoder {
			color: rgba($text, 0.5);
		}
	}
}

.multi-icon {
    display: flex;
    align-items: center;
    background: $light_background;
    border-bottom-right-radius: 8px;
    border-top-right-radius: 8px;

    line,
    path,
    polygon,
	circle{
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
            circle,
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

.copy-input {
	position: relative;
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

.iframe-output {
	position: absolute;
	right: -9999px;
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
        circle,
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
