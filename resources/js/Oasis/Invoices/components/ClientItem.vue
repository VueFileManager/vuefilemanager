<template>
    <div class="file-wrapper" @mouseup.stop="clickedItem" @dblclick="showClient">
        <div class="file-item" :class="{'is-clicked': isClicked , 'no-clicked': !isClicked && $isMobile()}">

			<!-- MultiSelecting for the mobile version -->
            <transition name="slide-from-left">
                <div class="check-select" v-if="isMobileSelectMode">
                    <div class="select-box" :class="{'select-box-active': isClicked } ">
                        <CheckIcon v-if="isClicked" class="icon" size="17" />
                    </div>
                </div>
            </transition>

			<!--Thumbnail for item-->
            <div class="icon-item">

				<!--Image thumbnail-->
                <img loading="lazy" class="image" :src="item.avatar" :alt="item.name" />
            </div>

            <!--Name-->
            <div class="item-name">

				<b :ref="item.id" class="name">
                    {{ item.name }}
                </b>

                <div class="item-info">
                    <span class="item-size">
						{{ $t('file_detail.created_at') }}: {{ item.created_at }}, {{ $t('global.total') }}: {{ item.totalNet }}
					</span>
                </div>
            </div>

            <!--Show item actions-->
            <transition name="slide-from-right">
                <div class="actions" v-if="$isMobile() && ! isMobileSelectMode">
                    <span @mousedown.stop="showItemActions" class="show-actions">
                        <MoreVerticalIcon size="16" class="icon-action text-theme" />
                    </span>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import {CheckIcon, MoreVerticalIcon} from 'vue-feather-icons'
import {mapGetters} from 'vuex'
import {events} from '@/bus'

export default {
    name: 'ClientItem',
    props: [
		'item'
	],
    components: {
        MoreVerticalIcon,
        CheckIcon,
    },
    computed: {
        ...mapGetters([
            'FilePreviewType',
            'clipboard',
            'entries'
        ]),
        isClicked() {
            return this.clipboard.some(element => element.id === this.item.id)
        },
    },
    data() {
        return {
            isMobileSelectMode: false
        }
    },
    methods: {
        showItemActions() {
            this.$store.commit('CLIPBOARD_CLEAR')
            this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)

            events.$emit('mobile-menu:show', 'client-menu')
        },
        clickedItem(e) {
            if (!this.$isMobile()) {

                if ((e.ctrlKey || e.metaKey) && !e.shiftKey) {

                	// Click + Ctrl
                    if (this.clipboard.some(item => item.data.id === this.item.data.id)) {
                        this.$store.commit('REMOVE_ITEM_FROM_CLIPBOARD', this.item)
                    } else {
                        this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)
                    }

                } else if (e.shiftKey) {

                	// Click + Shift
                    let lastItem = this.entries.indexOf(this.clipboard[this.clipboard.length - 1])
                    let clickedItem = this.entries.indexOf(this.item)

                    // If Click + Shift + Ctrl dont remove already selected items
                    if (!e.ctrlKey && !e.metaKey) {
                        this.$store.commit('CLIPBOARD_CLEAR')
                    }

                    //Shift selecting from top to bottom
                    if (lastItem < clickedItem) {
                        for (let i = lastItem; i <= clickedItem; i++) {
                            this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.entries[i])
                        }
                        //Shift selecting from bottom to top
                    } else {
                        for (let i = lastItem; i >= clickedItem; i--) {
                            this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.entries[i])
                        }
                    }

                } else {

                	// Click
                    this.$store.commit('CLIPBOARD_CLEAR')
                    this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)
                }
            }

            if (!this.isMobileSelectMode && this.$isMobile()) {
				this.$router.push({name: 'ClientDetail', params: {id: this.item.id}})
			}

            if (this.isMobileSelectMode && this.$isMobile()) {
                if (this.clipboard.some(item => item.data.id === this.item.data.id)) {
                    this.$store.commit('REMOVE_ITEM_FROM_CLIPBOARD', this.item)
                } else {
                    this.$store.commit('ADD_ITEM_TO_CLIPBOARD', this.item)
                }
            }
        },
        showClient() {
			this.$router.push({name: 'ClientDetail', params: {id: this.item.id}})
        },
    },
    created() {
        events.$on('mobileSelecting:start', () => {
            this.isMobileSelectMode = true
            this.$store.commit('CLIPBOARD_CLEAR')
        })

        events.$on('mobileSelecting:stop', () => {
            this.isMobileSelectMode = false
            this.$store.commit('CLIPBOARD_CLEAR')
        })
    }
}
</script>

<style scoped lang="scss">
@import '@assets/vuefilemanager/_variables';
@import '@assets/vuefilemanager/_mixins';


.slide-from-left-move {
    transition: transform 300s ease;
}

.slide-from-left-enter-active,
.slide-from-right-enter-active,
.slide-from-left-leave-active,
.slide-from-right-leave-active {
    transition: all 300ms;
}

.slide-from-left-enter,
.slide-from-left-leave-to {
    opacity: 0;
    transform: translateX(-100%);
}

.slide-from-right-enter,
.slide-from-right-leave-to {
    opacity: 0;
    transform: translateX(100%);
}


.check-select {
    margin-right: 15px;
    margin-left: 6px;

    .select-box {
        width: 20px;
        height: 20px;
        background-color: darken($light_background, 5%);
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
    }

    .select-box-active {
        background-color: $theme;

        .icon {
            stroke: white;
        }
    }
}

.file-wrapper {
    user-select: none;
    position: relative;

    &:hover {
        border-color: transparent;
    }

    .actions {
        text-align: right;
        width: 50px;

        .show-actions {
            cursor: pointer;
            padding: 12px 0 12px 6px;

            .icon-action {
                margin-top: 9px;
                @include font-size(14);

                circle {
                    color: inherit;
                }
            }
        }
    }

    .item-name {
        display: block;
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;

        .item-info {
            display: block;
            line-height: 1;
        }

        .item-shared {
            display: inline-block;

            .label {
                @include font-size(12);
                font-weight: 400;
                color: $theme;
            }

            .shared-icon {
                vertical-align: middle;

                path,
                circle,
                line {
                    color: inherit;
                }
            }
        }

        .item-size,
        .item-length {
            @include font-size(11);
            font-weight: 400;
            color: rgba($text, 0.7);
        }

        .name {
            white-space: nowrap;

            &[contenteditable] {
                -webkit-user-select: text;
                user-select: text;
            }

            &[contenteditable='true']:hover {
                text-decoration: underline;
            }
        }

        .name {
            color: $text;
            @include font-size(14);
            font-weight: 700;
            max-height: 40px;
            overflow: hidden;
            text-overflow: ellipsis;

            &.actived {
                max-height: initial;
            }
        }
    }

    &.selected {
        .file-item {
            background: $light_background;
        }
    }

    .icon-item {
        text-align: center;
        position: relative;
        flex: 0 0 50px;
        line-height: 0;
        margin-right: 20px;

        .folder {
            width: 52px;
            height: 52px;

            /deep/ .folder-icon {
                @include font-size(52)
            }
        }

        .file-icon {
            @include font-size(45);

            path {
                fill: #fafafc;
                stroke: #dfe0e8;
                stroke-width: 1;
            }
        }

        .file-icon-text {
            line-height: 1;
            top: 40%;
            @include font-size(11);
            margin: 0 auto;
            position: absolute;
            text-align: center;
            left: 0;
            right: 0;
            font-weight: 600;
            user-select: none;
            max-width: 50px;
            max-height: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .image {
            object-fit: cover;
            user-select: none;
            max-width: 100%;
            border-radius: 5px;
            width: 50px;
            height: 50px;
            pointer-events: none;
        }
    }

    .file-item {
        border: 2px dashed transparent;
        width: 100%;
        display: flex;
        align-items: center;
        padding: 7px 7px 7px 15px;

        &.is-dragenter {
            border-radius: 8px;
        }

        &.no-clicked {
            background: white !important;

            .item-name {
                .name {
                    color: $text !important;
                }
            }
        }

        &:hover,
        &.is-clicked {
            border-radius: 8px;
            background: $light_background;
        }
    }
}

@media (prefers-color-scheme: dark) {
    .check-select {

        .select-box {
            background-color: lighten($dark_mode_foreground, 10%);
        }

        .select-box-active {
            background-color: $theme;

            .icon {
                stroke: white;
            }
        }
    }

    .file-wrapper {
        .icon-item {
            .file-icon {
                path {
                    fill: $dark_mode_foreground;
                    stroke: #2f3c54;
                }
            }
        }

        .file-item {
            &.no-clicked {
                background: $dark_mode_background !important;

                .file-icon {

                    path {
                        fill: $dark_mode_foreground !important;
                        stroke: #2F3C54;
                    }
                }

                .item-name {

                    .name {
                        color: $dark_mode_text_primary !important;
                    }
                }
            }

            &:hover,
            &.is-clicked {
                background: $dark_mode_foreground;

                .file-icon {
                    path {
                        fill: $dark_mode_background;
                    }
                }
            }
        }

        .item-name {
            .name {
                color: $dark_mode_text_primary;
            }

            .item-size,
            .item-length {
                color: $dark_mode_text_secondary;
            }
        }
    }
}
</style>