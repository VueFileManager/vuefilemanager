<template>
    <div  v-if="isVisible" @click="close" class="sorting-preview" ref="contextmenu" >
       
        <div class="menu-options" id="menu-list">
            <ul class="menu-option-group">
                <li class="menu-option" >
                    <div class="icon">
                        <grid-icon size="17"/>
                    </div>
                    <div class="text-label">
                       Grid View
                    </div>
                </li>
                <li class="menu-option"> 
                    <div class="icon">
                        <list-icon size="17"/>
                    </div>
                    <div class="text-label">
                        List View
                    </div>
                </li>
                
            </ul>
            <ul class="menu-option-group">
                <li class="menu-option" @click="sort('date')">
                    <div class="icon">
                        <calendar-icon size="17"/>
                    </div>
                    <div class="text-label">
                        Sort By Date
                    </div>
                    <div class="sort-row" v-show="sorting.filed === 'date'" >
                        <chevron-up-icon/>
                    </div>
                </li>
                <li class="menu-option" @click="sort('name')"  >
                    <div class="icon">
                       <img class="aplhabet" src="/assets/icons/alphabet.svg" size="17">
                    </div>
                    <div class="text-label">
                       Sort By Aplhabet
                    </div>
                    <div class="sort-row" v-show="sorting.filed === 'name'">
                        <chevron-up-icon/>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { CalendarIcon,
        ListIcon,
        GridIcon,
        ChevronUpIcon 
        } from 'vue-feather-icons'
import { mapGetters } from 'vuex'
import { events } from '@/bus'

export default {
    name: 'SortingAndPreview',
    components: {
        CalendarIcon,
        ListIcon,
        GridIcon,
        ChevronUpIcon
    },
    data() {
        return {
            isVisible: false,
            sorting: {
                sort: 'DESC',
                field: undefined,
            },
        }
    },

    methods: {
        close() {
            // this.isVisible = false
        },
        sort(field) {
           
           this.sorting.field = field

            if (this.sorting.sort === 'DESC') {
                this.sorting.sort = 'ASC'
            } else if (this.sorting.sort === 'ASC') {
                this.sorting.sort = 'DESC'
            }

            console.log(this.sorting)

        }
    },
    mounted () {
        events.$on('sortingAndPreview-open' , () => {
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

.filePreviewFixed {
    position: fixed !important;
    display: flex;
}

.menu-option {
    display: flex;

    .icon {
        margin-right: 20px;
        line-height: 0;
        svg {
            color: red;
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
