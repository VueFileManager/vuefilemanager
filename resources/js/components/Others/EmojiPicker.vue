<template>
    <div class="select-emoji-wrapper">
        <label class="main-label">{{ $t('popup_rename.select_emoji_label') }}:</label>

        <!-- Selected Emoji input -->
        <div @click.stop="openList" class="select-input-wrapper focus-border-theme" :class="{'active-menu' : selectOpen}">

            <!-- If is emoji selected -->
            <div class="select-input" v-if="selectedEmoji && selectedEmoji !== 'default'">
                <div @click.stop="resetEmoji" class="select-input-icon-wrapper">
                    <x-icon size="14" class="select-input-icon" />
                </div>
                <Emoji class="emoji-preview" :emoji="selectedEmoji" location="emoji-picker-preview" />
                <span>{{ selectedEmoji.name }}</span>
            </div>

            <!-- If is emoji not selected -->
            <div class="not-selected" v-if="! selectedEmoji || selectedEmoji === 'default'">
                <span> {{ $t('popup_rename.set_emoji_input_placeholder') }}</span>
            </div>

            <chevron-down-icon class="row-icon" size="19" />
        </div>

        <!-- Emojis List -->
        <transition name="slide-in">
            <div v-if="selectOpen">

                <!-- Spinner -->
                <div v-if="!isLoadedEmojis" class="emoji-wrapper">
                    <Spinner />
                </div>

                <!-- List -->
                <div v-if="isLoadedEmojis && emojis" class="emoji-wrapper">

                    <!-- Search input -->
                    <input @click.stop @input="searchEmojis" v-model="searchInput" class="emoji-input" :placeholder="$t('popup_rename.search_emoji_input_placeholder')">

                    <!-- Navigation of Emojis Groups -->
                    <ul v-show="searchInput.length < 1" class="groups-list">
                        <li @click.stop="scrollToGroup(group.name)" v-for="(group,i) in emojis.emojisGroups" :key="i" class="group-option" :class="{'active' : group.name === groupInView}">
                            <Emoji :emoji="group.emoji" location="emoji-picker" />
                        </li>
                    </ul>

                    <!-- All Emojis -->
                    <div v-show="searchInput.length < 1" @scroll="checkGroupInView" id="group-box" class="group-wrapper">
                        <div v-for="(group, name) in allEmoji()" :key="name" class="options-wrapper" :id="`group-${name}`">
                            <label class="group-name-label">{{ name }}</label>
                            <ul class="options-list">
                                <li @click="setEmoji( emoji )" v-for="(emoji,i) in group" :key="i" class="option">
                                    <Emoji :emoji="emoji" location="emoji-picker" />
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Searched emojis -->
                    <div v-if="searchInput.length > 0" class="group-wrapper">
                        <div class="options-wrapper">
                            <ul class="options-list">
                                <li @click="setEmoji( emoji )" v-for="(emoji,i) in filteredEmojis" :key="i" class="option">
                                    <Emoji :emoji="emoji" location="emoji-picker" />
                                </li>
                            </ul>
                            <span class="not-found" v-if="filteredEmojis.length === 0 && filteredEmojisLoaded"> {{ $t('popup_rename.emoji_list_not_found') }}</span>
                            <Spinner v-if=" ! filteredEmojisLoaded " />
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import {ChevronDownIcon, XIcon} from 'vue-feather-icons'
import Spinner from '/resources/js/components/FilesView/Spinner'
import Emoji from '/resources/js/components/Others/Emoji'
import {mapGetters} from 'vuex'
import {groupBy} from 'lodash'
import {events} from '/resources/js/bus'


export default {
    name: 'EmojiPicker',
    props: ['pickedEmoji'],
    components: {
        ChevronDownIcon,
        Spinner,
        Emoji,
        XIcon,
    },
    computed: {
        ...mapGetters(['emojis']),
    },
    data() {
        return {
            selectedEmoji: this.pickedEmoji,
            searchInput: '',
            filteredEmojis: [],
            selectOpen: false,
            isLoadedEmojis: false,
            filteredEmojisLoaded: true,
            groupInView: 'Smileys & Emotion',
        }
    },
    methods: {
        allEmoji() {
            return groupBy(this.emojis.emojisList, 'group')
        },
        checkGroupInView: _.debounce(function () {

            this.emojis.emojisGroups.forEach(group => {

                let element = document.getElementById(`group-${group.name}`).getBoundingClientRect()
                let groupBox = document.getElementById('group-box').getBoundingClientRect()

                // Check if the group is in the viewport of group-box
                if (element.top < groupBox.top && element.bottom > groupBox.top) {
                    this.groupInView = group.name
                }
            })

        }, 200),
        scrollToGroup(name) {

            let group = document.getElementById(`group-${name}`)

            group.scrollIntoView({behavior: 'smooth'})

            this.groupInView = name
        },
        searchEmojis() {

            // Turn on spinner until filteredEmojis will loaded

            this.filteredEmojisLoaded = false

            this.filteredEmojis = [],

                this.filterEmojis()

        },
        filterEmojis: _.debounce(function () {

            this.filteredEmojis = this.emojis.emojisList.filter(emoji => emoji.name.includes(this.searchInput.toLowerCase()))

            this.filteredEmojisLoaded = true

        }, 800),
        openList() {
            this.isLoadedEmojis = false

            this.selectOpen = !this.selectOpen

            // Load emojis from server just if not loaded already
            if (this.selectOpen && !this.emojis) {

                axios.get('/assets/emojis.json')
                    .then(response => {
                        this.$store.commit('LOAD_EMOJIS_LIST', response.data)
                    })
                .finally(() => this.isLoadedEmojis = true)
            }

            // Simulate loading for the emojisList processing
            if (this.emojis) {
                setTimeout(() => {
                    this.isLoadedEmojis = true
                }, 20);
            }

            this.searchInput = ''

            this.groupInView = 'Smileys & Emotion'
        },
        setEmoji(value) {

            // Set emoji
            this.selectedEmoji = value

            this.$emit('input', value)

            this.selectOpen = false
        },
        resetEmoji() {
            this.selectedEmoji = undefined

            this.$emit('input', 'default')
        }
    },
    mounted() {
        this.selectOpen = false
    }
}
</script>

<style lang="scss" scoped>
@import "resources/sass/vuefilemanager/_inapp-forms.scss";
@import '/resources/sass/vuefilemanager/_forms';

.select-emoji-wrapper {
    margin-bottom: 20px;
}

.main-label {
    @include font-size(14);
    font-weight: 700;
    margin-bottom: 8px;
    display: block;
}

.emoji-wrapper {
    height: 400px;
    width: 100%;
    position: absolute;
    border: 1px solid transparent;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);
    border-radius: 8px;
    background: white;
    display: flex;
    flex-direction: column;
    padding: 10px;
    z-index: 10;
    top: 152px;


    .groups-list {
        display: grid;
        grid-template-columns: repeat(9, auto);
        justify-content: space-between;
        overflow-x: auto;
        overflow-y: hidden;
        height: 90px;

        .active {
            background: $light_background;
            border-radius: 8px;
        }

        .group-option {
            width: 45px;
            height: 45px;
            list-style: none;
            padding: 6px;
            cursor: pointer;

            &:hover {
                background: $light_background;
                border-radius: 8px;
            }
        }
    }

    .emoji-input {
        width: 100%;
        border-radius: 8px;
        margin-bottom: 20px;
        background: $light_background;
        border: none;
        padding: 13px 20px;
        font-weight: 700;

        &::placeholder {
            font-weight: 700;
            color: $light_text;
        }
    }

    .group-wrapper {
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        overflow-y: scroll;
        padding: 0px;

        .options-wrapper {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;

            &:last-child {
                margin-bottom: 0px;
            }

            .options-list {
                display: grid;
                grid-template-columns: repeat(auto-fill, 45px);
                justify-content: space-between;
                width: 100%;
            }

            .group-name-label {
                width: 100%;
                @include font-size(14);
                font-weight: 700;
                margin-bottom: 10px;
            }

            .option {
                list-style: none;
                width: 45px;
                height: 45px;
                padding: 6px;
                cursor: pointer;

                &:hover {
                    background: $light_background;
                    border-radius: 8px;
                }
            }

            .not-found {
                align-self: center;
                margin: auto;
                font-weight: 700;
                padding: 10px;
                border-radius: 8px;
                background: $light_background;
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);
            }
        }
    }
}

.select-input-wrapper {
    height: 50px;
    padding: 13px 20px;
    border: 1px solid transparent;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid transparent;
    @include transition(150ms);

    .row-icon {
        @include transition(150ms);
    }

    &.active-menu {
        //box-shadow: 0 0 7px rgba($theme, 0.3);

        .row-icon {
            transform: rotate(180deg);
        }
    }

    .select-input {
        @include font-size(16);
        font-weight: 700;
        display: flex;
        flex-direction: row;
        align-items: center;

        .emoji-preview {
            margin-left: 5px;
            margin-right: 10px;
            width: 22px;
            height: 22px;
        }

        .select-input-icon-wrapper {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: -7px;

            &:hover {

                .select-input-icon {
                    line {
                        stroke: $theme;
                    }
                }
            }

            .select-input-icon {
                line {
                    stroke: $text;
                }
            }
        }
    }

    .not-selected {
        span {
            color: rgba($text, 0.5);
            @include font-size(15);
            font-weight: 700
        }
    }
}

.slide-in-enter-active {
    transition: all 150ms ease;
}

.slide-in-enter {
    opacity: 0;
    transform: translateY(-210px);
}

.slide-in-enter-to {
    transform: translateY(-134px);
}

@media (max-width: 690px) {
    .emoji-wrapper {
        height: 300px;
    }
}

@media (max-width: 336px) {
    .emoji-wrapper {
        top: 173px;
    }
}

.dark {

    .emoji-wrapper {
        background: lighten($dark_mode_foreground, 2%);

        .emoji-input {
            background: $dark_mode_foreground;
        }

        .groups-list {
            .active {
                background: $dark_mode_foreground !important;
            }

            .group-option {
                &:hover {
                    background: $dark_mode_foreground !important;
                }
            }
        }

        .options-wrapper {
            .option {
                &:hover {
                    background: $dark_mode_foreground !important;
                }
            }

            .not-found {
                background: $dark_mode_foreground !important;
            }
        }
    }

    .select-input-wrapper {
        background: lighten($dark_mode_foreground, 3%);

        .not-selected {
            span {
                color: $dark_mode_text_secondary;
            }
        }

        .select-input-icon-wrapper {
            &:hover {
                .select-input-icon {
                    line {
                        stroke: $theme !important;
                    }
                }
            }

            .select-input-icon {
                line {
                    stroke: $dark_mode_text_primary !important;
                }
            }
        }
    }
}

</style>
