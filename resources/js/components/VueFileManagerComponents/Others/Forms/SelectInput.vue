<template>
    <div class="select">

        <!--Area-->
        <div class="input-area" :class="{'is-active': isOpen, 'is-error': isError}" @click="openMenu">

            <!--If is selected-->
            <div class="selected" v-if="selected">
                <div class="option-icon" v-if="selected.icon">
                    <FontAwesomeIcon :icon="selected.icon" />
                </div>
                <span class="option-value">{{ selected.label }}</span>
            </div>

            <!--If is empty-->
            <div class="not-selected" v-if="! selected">
                <span class="option-value placehoder">Selected your permision</span>
            </div>

            <FontAwesomeIcon icon="chevron-down" class="chevron"/>
        </div>

        <!--Options-->
        <transition name="slide-in">
            <ul class="input-options" v-if="isOpen">
                <li class="option-item" @click="selectOption(option)" v-for="(option, i) in options" :key="i">
                    <div class="option-icon" v-if="option.icon">
                        <FontAwesomeIcon :icon="option.icon" />
                    </div>
                    <span class="option-value">{{ option.label }}</span>
                </li>
            </ul>
        </transition>
    </div>
</template>

<script>
    export default {
        name:'SelectInput',
        props: ['options', 'isError', 'default'],
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
    @import "@assets/app.scss";

    .select {
        position: relative;
        user-select: none;
    }

    .input-options {
        background: $light_background;
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
        justify-content: space-between;
        background: $light_background;
        border: 1px solid transparent;
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
        @include font-size(12);
    }

    .option-value {
        @include font-size(15);
        font-weight: 700;
        width: 100%;

        &.placehoder {
            color: $light_text;
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
    }

</style>
