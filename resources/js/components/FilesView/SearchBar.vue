<template>
    <div class="search-bar">
        <input
                v-model="query"
                class="query"
                type="text"
                name="query"
                :placeholder="$t('inputs.placeholder_search_files')"
        />
        <div class="icon" v-if="!isQuery">
            <FontAwesomeIcon icon="search"></FontAwesomeIcon>
        </div>
        <div class="icon" v-if="isQuery" @click="resetQuery">
            <FontAwesomeIcon icon="times" class="pointer"></FontAwesomeIcon>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import {debounce} from 'lodash'
    import {events} from '@/bus'

    export default {
        name: 'SearchBar',
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
                            this.$store.dispatch('browseShared', [this.currentFolder, true])
                        } else {
                            this.$store.dispatch('getFolder', [this.currentFolder, true])
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
    @import "@assets/app.scss";

    .search-bar {
        position: relative;

        input {
            //width: 100%;
            background: $light_background;
            border-radius: 8px;
            outline: 0;
            padding: 9px 20px;
            font-weight: 100;
            @include font-size(16);
            min-width: 380px;
            transition: 0.15s all ease;
            border: 1px solid white;
            -webkit-appearance: none;

            &::placeholder {
                color: $text;
                @include font-size(14);
                font-weight: 400;
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
            right: 0;
            padding: 10px 15px;

            .pointer {
                cursor: pointer;
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .search-bar {
            input {
                background: $dark_mode_foreground;
                border-color: $dark_mode_foreground;

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
