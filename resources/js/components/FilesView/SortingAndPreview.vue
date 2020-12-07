<template>
     <div v-if="isVisible"  class="sorting-preview" >

        <div class="menu-options" id="menu-list">
            <ul class="menu-option-group">
                <li class="menu-option" @click="changePreview('grid')" >
                    <div class="icon">
                        <grid-icon size="17"/>
                    </div>
                    <div class="text-label">
                      {{$t('preview_sorting.grid_view')}}
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
                        {{$t('preview_sorting.list_view')}}
                    </div>
                    <div class="show-icon" v-if="isList">
                        <check-icon size="17"/>
                    </div>
                </li>
                
            </ul>
            <ul class="menu-option-group">
                <li class="menu-option" @click="sort('date')">
                    <div class="icon">
                        <calendar-icon size="17"/>
                    </div>
                    <div class="text-label">
                        {{$t('preview_sorting.sort_date')}}
                    </div>
                    <div class="show-icon" >
                        <arrow-up-icon size="17" v-if="filter.field === 'date'" :class="{ 'arrow-down': filter.sort === 'ASC' }"/>
                    </div>
                </li>
                <li class="menu-option" @click="sort('name')"  >
                    <div class="icon">
                       <img class="aplhabet" src="/assets/icons/alphabet.svg" size="17">
                    </div>
                    <div class="text-label">
                       {{$t('preview_sorting.sort_alphabet')}}
                    </div>
                    <div class="show-icon">
                        <arrow-up-icon size="17" v-if="filter.field === 'name'" :class="{ 'arrow-down': filter.sort === 'ASC' }"/>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

    import { CalendarIcon, ListIcon, GridIcon, ArrowUpIcon, CheckIcon } from 'vue-feather-icons'
    import { mapGetters } from 'vuex'
    import { events } from '@/bus'

    export default {
        name:'SortingAndPreview',
        components: {
            CalendarIcon,
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
            },
        },
        data () {
            return {
                isVisible: false,
                filter: {
                    sort: 'DESC',
                    field: undefined,
                }
            }
        },
        methods: {
            sort (field) {
                this.filter.field = field

                 // Set sorting direction
                if (this.filter.sort === 'DESC') {
                    this.filter.sort = 'ASC'
                } else if (this.filter.sort === 'ASC') {
                    this.filter.sort = 'DESC'
                }

                localStorage.setItem('sorting', JSON.stringify({sort: this.filter.sort , field: this.filter.field}))
                this.$getDataByLocation()
            },
            changePreview(previewType) {
                this.$store.dispatch('changePreviewType' , previewType)
            }
        },
        mounted () {

            let sorting = JSON.parse(localStorage.getItem('sorting'))

            this.filter.sort = sorting.sort
            this.filter.field = sorting.field

            events.$on('sortingAndPreview-open', () => {
                this.isVisible = true
            })

             events.$on('sortingAndPreview-close', () => {
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

.filePreviewFixed {
    position: fixed !important;
    display: flex;
}

.menu-option {
    display: flex;

    .icon {
        margin-right: 20px;
        line-height: 0;
        .alphabet {
            svg {
                fill: red !important;
                stroke: red !important;
            }
        }
    }

    .text-label {
        @include font-size(16);
    }
}

.sorting-preview {
    min-width: 250px;
    position: absolute;
    z-index: 99;
    box-shadow: $shadow;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    right: 66px;
    top: 63px;

    &.showed {
        display: block;
    }
}

.menu-options {
    list-style: none;
    width: 100%;
    margin: 0;
    padding: 0;

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
        white-space: nowrap;
        font-weight: 700;
        @include font-size(14);
        padding: 15px 20px;
        cursor: pointer;
        width: 100%;
        color: $text;

        &:hover {
            background: $light_background;

            .text-label {
                color: $theme;
            }

            path,
            line,
            polyline,
            rect,
            circle,
            polygon {
                stroke: $theme;
            }
        }
    }
}

@media (prefers-color-scheme: dark) {
    .sorting-preview {
        background: $dark_mode_foreground;

        .menu-options {
            .menu-option-group {
                border-color: $dark_mode_border_color;
            }

            .menu-option {
                color: $dark_mode_text_primary;

                &:hover {
                    background: rgba($theme, 0.1);
                }
            }
        }
    }
}
</style>
