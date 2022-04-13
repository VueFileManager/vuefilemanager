<template>
    <div class="select">
        <!--Area-->
        <div
            class="input-area rounded-lg bg-light-background dark:bg-2x-dark-foreground"
            :class="{ 'is-active': isOpen, '!border-rose-600': isError }"
            @click="openMenu"
        >
            <!--If is selected-->
            <div class="selected flex w-full items-center" v-if="selected">
                <div class="option-icon" v-if="selected.icon">
                    <user-icon v-if="selected.icon === 'user'" size="14" class="vue-feather text-theme" />
                    <edit2-icon v-if="selected.icon === 'user-edit'" size="14" class="vue-feather text-theme" />
                </div>
                <span class="option-value inline-block w-full overflow-hidden text-ellipsis whitespace-nowrap pl-2">
                    {{ selected.label }}
                </span>
            </div>

            <!--If is empty-->
            <div class="not-selected" v-if="!selected">
                <span class="option-value placehoder">{{ placeholder }}</span>
            </div>

            <chevron-down-icon size="19" class="chevron" />
        </div>

        <!--Options-->
        <transition name="slide-in">
            <div class="input-options rounded-lg" v-if="isOpen">
                <div v-if="options.length > 5" class="select-search">
                    <input
                        v-model="query"
                        ref="search"
                        type="text"
                        :placeholder="$te('search_in_list') ? $t('search_in_list') : 'Search in list...'"
                        class="search-input focus-border-theme rounded-lg"
                    />
                </div>
                <ul class="option-list">
                    <li class="option-item" @click="selectOption(option)" v-for="(option, i) in optionList" :key="i">
                        <div class="option-icon" v-if="option.icon">
                            <user-icon v-if="option.icon === 'user'" size="14" />
                            <edit2-icon v-if="option.icon === 'user-edit'" size="14" />
                        </div>
                        <span class="option-value">
							{{ $t(option.label) }}
						</span>
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>

<script>
import { ChevronDownIcon, Edit2Icon, UserIcon } from 'vue-feather-icons'
import { debounce, omitBy } from 'lodash'

export default {
    name: 'SelectInput',
    props: ['placeholder', 'options', 'isError', 'default'],
    components: {
        Edit2Icon,
        UserIcon,
        ChevronDownIcon,
    },
    watch: {
        query: debounce(function (val) {
            this.searchedResults = omitBy(this.options, (string) => {
                return !string.label.toLowerCase().includes(val.toLowerCase())
            })
        }, 200),
    },
    computed: {
        isSearching() {
            return this.searchedResults && this.query !== ''
        },
        optionList() {
            return this.isSearching ? this.searchedResults : this.options
        },
    },
    data() {
        return {
            searchedResults: undefined,
            selected: undefined,
            isOpen: false,
            query: '',
        }
    },
    methods: {
        selectOption(option) {
            // Emit selected
            this.$emit('input', option.value)
            this.$emit('change', option.value)

            // Get selected
            this.selected = option

            // Close menu
            this.isOpen = false
        },
        openMenu() {
            this.isOpen = !this.isOpen

            if (this.$refs.search && this.isOpen) {
                this.$nextTick(() => this.$refs.search.focus())
            }
        },
    },
    created() {
        if (this.default) this.selected = this.options.find((option) => option.value === this.default)
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

/* TODO: refactor to the tailwind */

.select {
    position: relative;
    user-select: none;
    width: 100%;
}

.select-search {
    background: white;
    position: sticky;
    top: 0;
    padding: 13px;

    .search-input {
        border: 1px solid transparent;
        background: $light_background;
        @include transition(150ms);
        @include font-size(14);
        padding: 13px 20px;
        appearance: none;
        font-weight: 700;
        outline: 0;
        width: 100%;
    }
}

.input-options {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
    background: white;
    position: absolute;
    overflow: hidden;
    top: 65px;
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
}

.input-area {
    border-width: 1px;
    border-style: solid;
    border-color: transparent;
    justify-content: space-between;
    @include transition(150ms);
    align-items: center;
    padding: 13px 20px;
    display: flex;
    outline: 0;
    width: 100%;
    cursor: pointer;

    .chevron {
        @include transition(150ms);
    }

    &.is-active {
        //box-shadow: 0 0 7px rgba($theme, 0.3);

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
}

.option-value {
    @include font-size(14);
    font-weight: 700;
    vertical-align: middle;

    &.placehoder {
        color: rgba($text, 0.5);
    }
}

.slide-in-enter-active {
    transition: all 150ms ease;
}

.slide-in-enter /* .list-leave-active below version 2.1.8 */ {
    opacity: 0;
    transform: translateY(-50px);
}

.dark {
    .select-search {
        background: $dark_mode_foreground;

        .search-input {
            background: $dark_mode_background;
        }
    }

    .popup-wrapper {
        .input-area {
            background: lighten($dark_mode_foreground, 3%);
        }
    }

    .input-options {
        background: $dark_mode_foreground;

        .option-item {
            border-bottom: none;

            &:hover {
                background: lighten($dark_mode_foreground, 5%);

                .option-icon {
                    path,
                    circle {
                        color: inherit;
                    }
                }
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
