<template>
    <div>
        <div v-if="transferEmoji && !isIos" :style="{width: `${size}px`, height: `${size}px`}"  v-html="transferEmoji"/>
        <span v-if="transferEmoji && isIos" :style="{fontSize: `${size}px`}">{{transferEmoji}}</span>
    </div>
    
</template>

<script>
import twemoji from 'twemoji'
export default {
    name: 'Emoji',
    props: ['emoji', 'size'],
    computed: {
        transferEmoji () {
            
            // Transfer single emoji to twemoji
            let tweomjiParse = twemoji.parse(this.emoji.char, {
                        folder: 'svg',
                        ext: '.svg',
                        attributes: () => ({
                            loading: 'lazy',
                        }) 
                    }) 

            // Check the OS, if is OS not iOS return transfered emoji to twemoji
            return !this.isIos ? tweomjiParse : this.emoji.char
        },
    },
    data () {
        return {
            isIos: false
        }
    },
    created () {
        this.isIos = this.$isIos()
    }
}
</script>

<style lang="scss" scoped>
@import "@assets/vue-file-manager/_inapp-forms.scss";
@import '@assets/vue-file-manager/_forms';


</style>

