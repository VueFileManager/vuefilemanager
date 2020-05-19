<template>
    <div class="search-bar">
        <div class="icon" v-if="!isQuery">
            <search-icon size="19"></search-icon>
        </div>
        <div class="icon" v-if="isQuery" @click="resetQuery">
            <x-icon class="pointer" size="19"></x-icon>
        </div>
        <input
                v-model="query"
                class="query"
                type="text"
                name="query"
                :placeholder="$t('inputs.placeholder_search_files')"
        />
    </div>
</template>

<script>
    import { SearchIcon, XIcon } from 'vue-feather-icons'
    import {mapGetters} from 'vuex'
    import {debounce} from 'lodash'
    import {events} from '@/bus'

    export default {
        name: 'SearchBar',
        components: {
            SearchIcon,
            XIcon,
        },
        computed: {
            ...mapGetters(['currentFolder']),
            isQuery() {
                return this.query !== '' && typeof this.query !== 'undefined'
            }
        },
        data() {
            return {
                query: ''
            }
        },
        watch: {
            query(val) {
                return this.getResult(val)
            }
        },
        methods: {
            resetQuery() {
                this.query = ''
            },
            getResult: debounce(function (value) {
                if (this.isQuery) {
                    // Get search result if query is not empty
                    this.$store.dispatch('getSearchResult', value)
                } else if (typeof value !== 'undefined') {
                    if (this.currentFolder) {

                        // Get back after delete query to previosly folder
                        if ( this.$isThisLocation('public') ) {
                            this.$store.dispatch('browseShared', [{folder: this.currentFolder, back: true, init: false}])
                        } else {
                            this.$store.dispatch('getFolder', [{folder: this.currentFolder, back: true, init: false}])
                        }
                    }

                    this.$store.commit('CHANGE_SEARCHING_STATE', false)
                }
            }, 300)
        },
        created() {
            events.$on('clear-query', () => (this.query = undefined))
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

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
                border: 1px solid $theme;
                box-shadow: 0 0 7px rgba($theme, 0.3);
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
