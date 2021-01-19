<template>
    <div>
        <div class="select-table" >
            <div :class="{'active' : activeTab === child.title}" @click="setActiveTab(child)" v-for="(child, i) in tabList" :key="i"> 
                <mail-icon v-if="child.icon === 'email'" class="icon" size="17"/> 
                <link-icon v-if="child.icon === 'link'" class="icon" size="17"/>
                <smile-icon v-if="child.icon === 'emoji'" class="icon" size="17"/>
                <folder-icon v-if="child.icon === 'folder'" class="icon" size="17"/>
                <b>{{child.title}}</b>
            </div>
        </div>
        <slot></slot>
    </div>

</template>

<script>
     import {
        LinkIcon,
        MailIcon,
        SmileIcon,
        FolderIcon } from 'vue-feather-icons'

    export default {
        name: "TableWrapper",
        components: {
            LinkIcon,
            MailIcon,
            SmileIcon,
            FolderIcon
        },
        data () {
            return {
                tabList: [],
                activeTab: undefined
            }
        },
        methods: {
            setActiveTab(tab){

                // Set false active tab for all TableWrapper childrens
                this.$children.map(child => {
                    if(child._props.title !== tab.title)
                        child._data.activeTab = false    
                })

                // Set active tab for clicked cildren
                let child = this.$children.find(child => child._props.title === tab.title)._data.activeTab = true

                this.activeTab = tab.title
            },
        },
        mounted () {

            //Get all TableWrapper childrens and push to tabList
            this.$children.map(child => {
                this.tabList.push(child._props)
            })

            // Set active tab the first one 
            this.activeTab = this.$children[0]._props.title
            this.$children[0]._data.activeTab = true
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/vue-file-manager/_inapp-forms.scss";
    @import '@assets/vue-file-manager/_forms';

    .select-table {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
        cursor: pointer;
        
        & > * {
            width: 100%;
            height: 42px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: $light_background;
            color: $text;
        }
        & > :first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        & > :last-child {
             border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .icon {
            margin-right: 10px;
            path,
            circle,
            line,
            polyline {
                color: $theme !important;
            }
        }

        .active {
            background: $text;
                b {
                    color: $light_background !important;
                }
        }
    }


    @media (prefers-color-scheme: dark) {
        .select-table {   
            & > * {
                background: $dark_mode_foreground;
                color: $dark_mode_text_primary;
            }

            .active {
            background: $dark_mode_text_primary;
                h1 {
                    color: $dark_mode_foreground !important;
                }
            }
        }
    }

</style>
