<template>
    <div>
        <div v-if="transferEmoji && !isApple"  v-html="transferEmoji"/>
        <span v-if="transferEmoji && isApple" class="apple-emoji">{{transferEmoji}}</span>
    </div>
    
</template>

<script>
import twemoji from 'twemoji'
export default {
    name: 'Emoji',
    props: ['emoji'],
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
            return !this.isApple ? tweomjiParse : this.emoji.char
        },
    },
    data () {
        return {
            isApple: false
        }
    },
    created () {
        this.isApple = this.$isApple()
    }
}
</script>

<style lang="scss" scoped>
@import "@assets/vue-file-manager/_inapp-forms.scss";
@import '@assets/vue-file-manager/_forms';


</style>

