<template>
    <div class="table-wrapper">
        <slot>
            <child @click="setTab"></child>
        </slot>
    </div>
</template>

<script>
    import TableOption from '@/components/Others/TableOption'
    export default {
        name: "TableWrapper",
        components: {TableOption},
        data () {
            return {
                tabList: []
            }
        },
        methods: {
            setTab(tab){
                console.log('setTab')
                this.$children.find(child => child._props.title === tab)
            }
        },
        mounted () {
            this.$children.map(child => {
                this.tabList.push(child._props.title)
            })
            console.log(this.$children)
        }
        
    }
</script>

<style scoped lang="scss">
    @import "@assets/vue-file-manager/_inapp-forms.scss";
    @import '@assets/vue-file-manager/_forms';

    .table-wrapper {
        display: flex;
        justify-content: center;
        padding: 0px 20px;
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
        
    }

    @media (prefers-color-scheme: dark) {
        .table-wrapper {   
            & > * {
                background: $dark_mode_foreground;
                color: $dark_mode_text_primary;
            }
        }
    }

</style>
