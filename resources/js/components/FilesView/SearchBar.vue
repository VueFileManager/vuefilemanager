<template>
    <div class="search-bar">
        <div class="icon" v-if="!isQuery">
            <search-icon size="19" />
        </div>
        <div class="icon" v-if="isQuery" @click="resetQuery">
            <x-icon class="pointer" size="19" />
        </div>
        <input
            v-model="query"
            @input="$emit('input', query)"
            class="query focus-border-theme"
            type="text"
            :placeholder="placeholder"
        />
    </div>
</template>

<script>
    import {SearchIcon, XIcon} from 'vue-feather-icons'
    import {events} from '@/bus'

    export default {
        name: 'DesktopSearchBar',
        props: [
            'placeholder'
        ],
        components: {
            SearchIcon,
            XIcon,
        },
        computed: {
            isQuery() {
                return this.query !== '' && typeof this.query !== 'undefined'
            }
        },
        data() {
            return {
                query: ''
            }
        },
        methods: {
            resetQuery() {
                this.query = ''
                this.$emit('reset-query')
            }
        },
        created() {
            events.$on('clear-query', () => this.query = undefined)
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

    .search-bar {
        position: relative;

        input {
            background: transparent;
            border-radius: 8px;
            outline: 0;
            padding: 9px 20px 9px 43px;
            font-weight: 400;
            @include font-size(16);
            min-width: 175px;
            transition: 0.15s all ease;
            border: 1px solid white;
            -webkit-appearance: none;

            &::placeholder {
                color: $text;
                @include font-size(14);
                font-weight: 500;
            }

            &:focus {
                //box-shadow: 0 0 7px rgba($theme, 0.3);
            }

            &:focus + .icon {
                path {
                    fill: $theme;
                }
            }
        }

        .icon {
            position: absolute;
            top: 0;
            left: 0;
            padding: 11px 15px;

            .pointer {
                cursor: pointer;
            }
        }
    }

    @media only screen and (max-width: 1024px) {

        .search-bar input {
            max-width: 190px;
            padding-right: 0;
        }
    }

    @media only screen and (max-width: 690px) {

        .search-bar {

            input {
                min-width: initial;
                width: 100%;
                max-width: initial;
                padding: 9px 20px 9px 30px;

                &:focus {
                    border: 1px solid transparent;
                    box-shadow: none;
                }
            }

            .icon {
                padding: 11px 15px 11px 0;
            }
        }

    }

    @media (prefers-color-scheme: dark) {
        .search-bar {
            input {
                border-color: transparent;
                color: $dark_mode_text_primary;

                &::placeholder {
                    color: $dark_mode_text_secondary;
                }
            }

            .icon svg path {
                fill: $dark_mode_text_secondary;
            }
        }
    }
</style>
