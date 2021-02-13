<template>
    <div>
        <span v-if="transferEmoji && !isApple" class="twemoji-emoji" v-html="transferEmoji"></span>
        <span v-if="transferEmoji && isApple" class="apple-emoji">{{ transferEmoji }}</span>
    </div>
</template>

<script>
import twemoji from 'twemoji'

export default {
    name: 'Emoji',
    props: ['emoji'],
    computed: {
        transferEmoji() {

            // Transfer single emoji to twemoji
            let tweomjiParse = twemoji.parse(this.emoji.char, {
                folder: 'svg',
                ext: '.svg',
                attributes: () => ({
                    loading: 'lazy'
                })
            })

            // Check the OS, if is OS not iOS return transfered emoji to twemoji
            return !this.isApple ? tweomjiParse : this.emoji.char
        }
    },
    data() {
        return {
            isApple: this.$isApple()
        }
    }
}
</script>

<style lang="scss" scoped>
@import "@assets/vue-file-manager/_inapp-forms.scss";
@import '@assets/vue-file-manager/_forms';

.apple-emoji {
    font-size: 49px;
    line-height: 1.2;
}

</style>

