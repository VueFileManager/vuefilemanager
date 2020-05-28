<template>
    <div class="select">

        <!--Area-->
        <div class="input-area" :class="{'is-active': isOpen, 'is-error': isError}" @click="openMenu">

            <!--If is selected-->
            <div class="selected" v-if="selected">
                <div class="option-icon" v-if="selected.icon">
                    <user-icon v-if="selected.icon === 'user'" size="14"></user-icon>
                    <edit2-icon v-if="selected.icon === 'user-edit'" size="14"></edit2-icon>
                </div>
                <span class="option-value">{{ selected.label }}</span>
            </div>

            <!--If is empty-->
            <div class="not-selected" v-if="! selected">
                <span class="option-value placehoder">{{ placeholder }}</span>
            </div>

            <chevron-down-icon size="19" class="chevron"></chevron-down-icon>
        </div>

        <!--Options-->
        <transition name="slide-in">
            <ul class="input-options" v-if="isOpen">
                <li class="option-item" @click="selectOption(option)" v-for="(option, i) in options" :key="i">
                    <div class="option-icon" v-if="option.icon">
                        <user-icon v-if="option.icon === 'user'" size="14"></user-icon>
                        <edit2-icon v-if="option.icon === 'user-edit'" size="14"></edit2-icon>
                    </div>
                    <span class="option-value">{{ option.label }}</span>
                </li>
            </ul>
        </transition>
    </div>
</template>

<script>
    import { ChevronDownIcon, Edit2Icon, UserIcon } from 'vue-feather-icons'

    export default {
        name:'SelectInput',
        props: ['options', 'isError', 'default', 'placeholder'],
        components: {
            Edit2Icon,
            UserIcon,
            ChevronDownIcon
        },
        data() {
            return {
                selected: undefined,
                isOpen: false,
            }
        },
        methods: {
            selectOption(option) {

                // Emit selected
                this.$emit('input', option.value)

                // Get selected
                this.selected = option

                // Close menu
                this.isOpen = false
            },
            openMenu() {
                this.isOpen = ! this.isOpen
            },
        },
        created() {

            if (this.default)
                this.selected = this.options.find(option => option.value === this.default)
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .select {
        position: relative;
        user-select: none;
        width: 100%;
    }

    .input-options {
        background: $light_mode_input_background;
        border-radius: 8px;
        position: absolute;
        overflow: hidden;
        top: 65px;
        left: 0;
        right: 0;
        z-index: 9;

        .option-item {
            padding: 13px 20px;
            display: block;
            border-bottom: 1px solid #EBEBEB;
            cursor: pointer;

            &:hover {
                color: $theme;
                background: rgba($theme, .1);
            }

            &:last-child {
                border-bottom: none;
            }
        }
    }

    .input-area {
        border: 1px solid #ebebeb;
        justify-content: space-between;
        background: $light_mode_input_background;
        @include transition(150ms);
        align-items: center;
        border-radius: 8px;
        padding: 13px 20px;
        display: flex;
        outline: 0;
        width: 100%;
        cursor: pointer;

        .chevron {
            @include transition(150ms);
        }

        &.is-active {
            border-color: $theme;
            box-shadow: 0 0 7px rgba($theme, 0.3);

            .chevron {
                @include transform(rotate(180deg));
            }
        }

        &.is-error {
            border-color: $danger;
            box-shadow: 0 0 7px rgba($danger, 0.3);
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

    .slide-in-enter-active {
        transition: all 150ms ease;
    }

    .slide-in-enter /* .list-leave-active below version 2.1.8 */
    {
        opacity: 0;
        transform: translateY(-50px);
    }

    @media (prefers-color-scheme: dark) {

        .input-area {
            background: $dark_mode_foreground;
            border-color: $dark_mode_foreground;

            .option-icon {
                path {
                    fill: $theme
                }
            }
        }

        .input-options {
            background: $dark_mode_foreground;

            .option-item {
                border-bottom: none;

                &:hover {
                    color: $theme;
                    background: rgba($theme, .1);
                }

                &:last-child {
                    border-bottom: none;
                }
            }
        }

        .option-value {

            &.placehoder {
                color: $dark_mode_text_secondary;
            }
        }
    }

</style>
