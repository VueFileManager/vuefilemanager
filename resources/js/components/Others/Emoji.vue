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
            return !this.isIos 
                   ? twemoji.parse(this.emoji.char, {
                        folder: 'svg',
                        ext: '.svg',
                        attributes: () => ({
                            loading: 'lazy',
                        }) 
                    }) 
                    : this.emoji.char
        },
    },
    data () {
        return {
            isIos: false
        }
    },
    created () {
       
        const toMatch = [
                /iPhone/i,
                /iPad/i,
                /iPod/i,
                /iOS/i,
                /macOS/i,
                /Macintosh/i
            ]
        this.isIos = toMatch.some(toMatchItem => {
            return navigator.userAgent.match(toMatchItem)
        })
    }
}
</script>

<style lang="scss" scoped>
@import "@assets/vue-file-manager/_inapp-forms.scss";
@import '@assets/vue-file-manager/_forms';


</style>

