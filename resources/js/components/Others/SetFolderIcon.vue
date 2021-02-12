<template>
    <div class="set-folder-icon">

        <TabWrapper >

            <!-- Emojis -->
            <TabOption :selected="true" id="emoji-list" :title="$t('popup_rename.tab_emoji_title')" icon="emoji">
                <div class="select-emoji-wrapper">
                    <label class="main-label">{{$t('popup_rename.select_emoji_label')}}:</label>

                    <!-- Selected Emoji input -->
                    <div @click.stop="openMenu"  class="select-input-wrapper" :class="{'active-menu' : selectOpen}">

                        <!-- If is emoji selected -->
                        <div class="select-input" v-if="selectedEmoji">
                            <div @click.stop="resetEmoji" class="select-input-icon-wrapper">
                                <x-icon size="14" class="select-input-icon"/>
                            </div>
                            <Emoji class="emoji-preview" :emoji="selectedEmoji" size="25"></Emoji>
                            <span>{{selectedEmoji.name}}</span>
                        </div>
                    
                        <!-- If is emoji not selected -->
                        <div class="not-selected" v-if="! selectedEmoji">
                            <span> {{$t('popup_rename.set_emoji_input_placeholder')}}</span>
                        </div>

                        <chevron-down-icon class="row-icon" size="19"/>

                    </div>

                    <!-- Emojis List -->
                    <transition name="slide-in">
                        <div v-if="selectOpen">
                            <!-- Spinner -->
                            <div v-if="!loadedList" class="emoji-wrapper">
                                <Spinner />
                            </div>
                            
                            <!-- List -->
                            <div v-if="loadedList && emojis" class="emoji-wrapper">

                                <!-- Search input -->
                                <input @click.stop @input="filterEmojis" v-model="searchInput" class="emoji-input" :placeholder="$t('popup_rename.search_emoji_input_placeholder')" >

                                <!-- Navigation of Emojis Groups -->
                                <ul v-show="searchInput.length < 1" class="groups-list">
                                    <li @click.stop="scrollToGroup(group.name)" v-for="(group,i) in emojis.emojisGroups" :key="i" class="group-option" :class="{'active' : group.name === groupInView}">
                                        <Emoji :emoji="group.emoji" size="33"/>
                                    </li>
                                </ul>

                                <!-- All Emojis -->
                                <div v-show="searchInput.length < 1" @scroll="checkGroupInView" id="group-box"  class="group-wrapper">
                                    <div v-for="(group, name) in allEmoji" :key="name" class="options-wrapper" :id="`group-${name}`">
                                        <label class="group-name-label">{{name}}</label>
                                        <ul class="options-list">
                                            <li @click="setIcon({'emoji':emoji})" v-for="(emoji,i) in group" :key="i"  class="option"> 
                                                <Emoji :emoji="emoji" size="33"/>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                            <!-- Searched emojis -->
                                <div v-if="searchInput.length > 0" class="group-wrapper">
                                    <div class="options-wrapper">
                                        <ul class="options-list">
                                            <li @click="setIcon({'emoji':emoji})" v-for="(emoji,i) in filteredEmojis" :key="i" class="option" >
                                                <Emoji :emoji="emoji" size="33"/>    
                                            </li>
                                        </ul>
                                        <span class="not-found" v-if="filteredEmojis.length === 0"> {{$t('popup_rename.emoji_list_not_found')}}</span>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </transition>
                </div>
            </TabOption>

            <!-- Colors -->
            <TabOption :title="$t('popup_rename.tab_color_title')" icon="folder">
                <div class="color-pick-wrapper">
                <label class="main-label">{{$t('popup_rename.color_pick_label')}}:</label>
                    <ul class="color-wrapper">
                        <li  v-for="(color, index) in colors"
                            :key="index"
                            @click="setIcon({'color': color})"
                            class="single-color" 
                            :class="{'active-color': color === selectedColor }" 
                            :style="{background:color}" />
                    </ul>
                </div>
            </TabOption>
        </TabWrapper>
    </div>
</template>

<script>
import { SmileIcon, FolderIcon, ChevronDownIcon, XIcon } from 'vue-feather-icons'
import TabWrapper from '@/components/Others/TabWrapper'
import TabOption from '@/components/Others/TabOption'
import Spinner from '@/components/FilesView/Spinner' 
import Emoji from '@/components/Others/Emoji'
import lodash from 'lodash'
import { mapGetters } from 'vuex'
import { events } from '@/bus'

export default {
    name: "SetFolderIcon",
    props: ['folderData', 'unique_id'],
    components: {
        ChevronDownIcon ,
        TabWrapper,
        TabOption,
        FolderIcon,
        SmileIcon,
        Spinner,
        XIcon,
        Emoji
    },
    computed: {
        ...mapGetters(['emojis']),
        allEmoji() {
            return  _.groupBy(this.emojis.emojisList, 'group')                
        },
    },
    data () {
        return {
            selectedEmoji: undefined,
            selectedColor: undefined,
            searchInput: '',
            filteredEmojis: [],
            selectOpen: false,
            loadedList: false,
            groupInView: 'Smileys & Emotion',
            colors: [ '#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6', 
                    '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
                    '#80B300', '#809900', '#E6B3B3', '#6680B3' ]
        }
    },
    methods: {
        checkGroupInView: _.debounce( function() {

            this.emojis.emojisGroups.forEach( group => { 

                let element = document.getElementById(`group-${group.name}`).getBoundingClientRect()
                let groupBox = document.getElementById('group-box').getBoundingClientRect()

                // Check if the group is in the viewport of group-box
                if(element.top < groupBox.top && element.bottom > groupBox.top){
                    this.groupInView = group.name
                }
            })

        }, 200),
        scrollToGroup( name ) {

            let group = document.getElementById( `group-${name}` )

            group.scrollIntoView({ behavior: "smooth" })

            this.groupInView = name
        },
        filterEmojis: _.debounce(function( emoji ){

            this.filteredEmojis = this.emojis.emojisList.filter(emoji => emoji.name.includes(this.searchInput))

        }, 800),
        openMenu() {

            this.selectOpen = ! this.selectOpen

            //Load emojis
            if( this.selectOpen ){
                this.$store.dispatch('getEmojisList').then(( loaded ) => {
                    this.loadedList = loaded
                })
            }

            if( ! this.selectOpen )
                this.loadedList = false

            this.searchInput = ''

            this.groupInView = 'Smileys & Emotion'
        },
        setIcon( value ) {

            // Set emoji
            if( value.emoji ){
                this.selectedEmoji = value.emoji
                this.selectedColor = undefined
            }
                
            // Set color
            if( value.color ) {
                this.selectedColor = value.color
                this.selectedEmoji = undefined
            }
            
            events.$emit('setFolderIcon', { 'value':value })

            this.selectOpen = false
        },
        resetEmoji(){

            this.selectedEmoji = undefined

            events.$emit('setFolderIcon', { 'value': 'default' })
        }
    },
    mounted () {
        
        this.selectOpen = false

        // If folder have already set some emoji set this emoji to selected emoji
        this.folderData.icon_emoji ? this.selectedEmoji = this.folderData.icon_emoji : ''

        // If folder have already set some color set this color to selected color
        this.folderData.icon_color ? this.selectedColor = this.folderData.icon_color : ''

        events.$on('unClick', () => {
            
            this.selectOpen = false

            this.loadedList = false
        })
    }
}
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_inapp-forms.scss";
@import '@assets/vue-file-manager/_forms';

.color-pick-wrapper {
    .color-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 20px;
        .single-color {
            width: 40px;
            height: 40px;
            list-style: none;
            margin: 8px;
            border-radius: 8px;
            cursor: pointer;

                &.active-color {
                border: 2px solid $text;
            }

            &:hover {
                border: 2px solid $text;
            }
        }
    }
}

.select-emoji-wrapper{
    margin-bottom: 20px;
}

.main-label {
    @include font-size(14);
    font-weight: 700;
    margin-bottom: 8px;
    display: block;
}

.emoji-wrapper {
    height: 350px;
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
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            margin-bottom: 20px;

            .active {
                background: $light_background;
                border-radius: 8px;
            }

            .group-option {
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
        }

    .emoji-input {
        width: 100%;
        border-radius: 8px;
        padding: 4px;
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
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
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
                margin:auto;
                font-weight: 700;
                padding: 10px;
                border-radius: 8px;
                background:$light_background ;
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
        border-color: $theme;
        box-shadow: 0 0 7px rgba($theme, 0.3);

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
            width: 25px;
            height: 25px;
            margin-right: 10px;
            margin-left: 6px;
        }

        .select-input-icon-wrapper {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;

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

.set-folder-icon {
    position: relative;
}

.slide-in-enter-active
{
    transition: all 150ms ease;
}

.slide-in-enter
{
    opacity: 0;
    transform: translateY(-210px);
}

.slide-in-enter-to
{
    transform: translateY(-134px);
}

@media (max-width: 336px) {
    .emoji-wrapper {
        top: 173px;
    }
}

@media (prefers-color-scheme: dark) {
    .color-pick-wrapper{
        .color-wrapper{
            .single-color {
                &.active-color {
                    border: 2px solid ;
                }
                &:hover {
                    border: 2px solid $dark_mode_text_primary;
                }  
            }
        }
    }

    .emoji-wrapper {
        background: $dark_mode_background;
        .emoji-input {
            background: $dark_mode_foreground ;
        }
        .groups-list{
            .active{
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
        background: $dark_mode_foreground;
        .not-selected {
            span {
                color:$dark_mode_text_secondary;
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
                    stroke:$dark_mode_text_primary !important;
                }
            }            
        }
    }
}

</style>
