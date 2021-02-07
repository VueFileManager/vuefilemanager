<template>
    <div v-show="transferEmoji" :style="{width: `${size}px`, height: `${size}px`}"  v-html="transferEmoji"/>
</template>

<script>
import twemoji from 'twemoji'
    export default {
        name: 'Emoji',
        props: ['emoji', 'size'],
        computed: {
            transferEmoji () {
                
                // Transfer single emoji to twemoji
                return twemoji.parse(this.emoji.char, {
                    folder: 'svg',
                    ext: '.svg',
                    attributes: () => ({
                        loading: 'lazy',
                    }) 
                })
            }
        },
    }
</script>

