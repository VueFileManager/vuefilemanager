<template>
    <div class="options-wrapper">
        <transition name="context-menu">
            <div class="options" v-if="isVisible">
                <div class="menu-options">

                    <ul class="menu-option-group">
                        <li class="menu-option" @click="changePreview('grid')">
                            <div class="icon">
                                <grid-icon size="17"/>
                            </div>
                            <div class="text-label">
                                {{ $t('preview_sorting.grid_view') }}
                            </div>
                            <div class="show-icon" v-if="isGrid">
                                <check-icon size="17"/>
                            </div>
                        </li>
                        <li class="menu-option" @click="changePreview('list')">
                            <div class="icon">
                                <list-icon size="17"/>
                            </div>
                            <div class="text-label">
                                {{ $t('preview_sorting.list_view') }}
                            </div>
                            <div class="show-icon" v-if="isList">
                                <check-icon size="17"/>
                            </div>
                        </li>
                    </ul>

                    <ul class="menu-option-group">
                        <!--TODO: co to ten c class atribute? :D -->
                        <li c class="menu-option" @click="sort('created_at')">
                            <div class="icon">
                                <calendar-icon size="17"/>
                            </div>
                            <div class="text-label">
                                {{ $t('preview_sorting.sort_date') }}
                            </div>
                            <div class="show-icon">
                                <arrow-up-icon size="17" v-if="filter.field === 'created_at'" :class="{ 'arrow-down': filter.sort === 'ASC' }"/>
                            </div>
                        </li>
                        <li class="menu-option" @click="sort('name')">
                            <div class="icon">
                                <alphabet-icon size="17" class="aplhabet-icon"/>
                            </div>
                            <div class="text-label">
                                {{ $t('preview_sorting.sort_alphabet') }}
                            </div>
                            <div class="show-icon">
                                <arrow-up-icon size="17" v-if="filter.field === 'name'" :class="{ 'arrow-down': filter.sort === 'ASC' }"/>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </transition>

        <!-- TODO: tento fade tu nemusi byt, mame uz jeden hlavny implementovany pre appku, iba odpalit spravny event, pozri Vignette.vue-->
        <transition name="fade">
            <div v-show="isVisible" class="vignette" @click.self="close"></div>
        </transition>
    </div>
</template>

<script>

import { CalendarIcon, ListIcon, GridIcon, ArrowUpIcon, CheckIcon } from 'vue-feather-icons'
import AlphabetIcon from '@/components/FilesView/Icons/AlphabetIcon'
import { mapGetters } from 'vuex'
import { events } from '@/bus'

export default {
    name: 'MobileSortingAndPreview',
    components: {
        CalendarIcon,
        AlphabetIcon,
        ArrowUpIcon,
        CheckIcon,
        ListIcon,
        GridIcon
    },
    computed: {
        ...mapGetters(['FilePreviewType']),
        isGrid() {
            return this.FilePreviewType === 'grid'
        },
        isList() {
            return this.FilePreviewType === 'list'
        }
    },
    data() {
        return {
            isVisible: false,
            filter: {
                sort: 'DESC',
                field: undefined
            }
        }
    },
    methods: {
        close() {
            this.isVisible = false
            events.$emit('mobileSortingAndPreview-close')
        },
        sort(field) {

            this.filter.field = field

            // Set sorting direction
            if (this.filter.sort === 'DESC') {
                this.filter.sort = 'ASC'
            } else if (this.filter.sort === 'ASC') {
                this.filter.sort = 'DESC'
            }

            // Save to localStorage sorting options
            localStorage.setItem('sorting', JSON.stringify({ sort: this.filter.sort, field: this.filter.field }))

            // Update sorting state in vuex
            this.$store.commit('UPDATE_SORTING')

            // Get data using the application location
            this.$getDataByLocation()
        },
        changePreview(previewType) {
            this.$store.dispatch('changePreviewType', previewType)
        }
    },
    mounted() {

        let sorting = JSON.parse(localStorage.getItem('sorting'))

        // Set default sorting if is not setup in LocalStorage
        this.filter.sort = sorting ? sorting.sort : 'DESC'
        this.filter.field = sorting ? sorting.field : 'created_at'

        // TODO: tento event by som zrefaktoroval do jedneho, nech mame menej kodu pre takuto jednoduchu operaciu
        events.$on('mobileSortingAndPreview-open', () => {
            this.isVisible = true
        })

        events.$on('mobileSortingAndPreview-close', () => {
            this.isVisible = false
        })
    }
}
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.show-icon {
    margin-left: auto;
    max-height: 19px;

    .arrow-down {
        @include transform(rotate(180deg));
    }
}

.icon {
    margin-right: 20px;
    line-height: 0;

    /*
    TODO: preklep
    */
    .aplhabet-icon {
        /deep/ line,
        /deep/ polyline {
            stroke: $text;
        }
    }

}

.menu-option {
    display: flex;
    align-items: center;

    .icon {
        margin-right: 20px;
        line-height: 0;
    }

    .text-label {
        @include font-size(16);
    }
}

.vignette {
    background: rgba(0, 0, 0, 0.35);
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 9;
    cursor: pointer;
    opacity: 1;
}

.options {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 99;
    overflow: hidden;
    background: white;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;

    .menu-options {
        margin-top: 10px;
        list-style: none;
        width: 100%;

        .menu-option-group {
            padding: 5px 0;
            border-bottom: 1px solid $light_mode_border;

            &:first-child {
                padding-top: 0;
            }

            &:last-child {
                padding-bottom: 0;
                border-bottom: none;
            }
        }

        .menu-option {
            font-weight: 700;
            letter-spacing: 0.15px;
            @include font-size(14);
            cursor: pointer;
            width: 100%;
            padding: 17px 20px;
            text-align: center;

            &:last-child {
                border: none;
            }
        }
    }
}

@media (prefers-color-scheme: dark) {
    .vignette {
        background: $dark_mode_vignette;
    }

    .options {
        background: $dark_mode_background;

        .menu-options {
            background: $dark_mode_background;

            .menu-option-group {
                border-color: $dark_mode_border_color;
            }

            .menu-option {
                color: $dark_mode_text_primary;
            }
        }
    }
    .icon {
        .aplhabet-icon {
            /deep/ line,
            /deep/ polyline {
                stroke: $dark_mode_text_primary;
            }
        }
    }
}

// Transition
.context-menu-enter-active,
.fade-enter-active {
    transition: all 200ms;
}

.context-menu-leave-active,
.fade-leave-active {
    transition: all 200ms;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.context-menu-enter,
.context-menu-leave-to {
    opacity: 0;
    transform: translateY(100%);
}

.context-menu-leave-active {
    position: absolute;
}
</style>
