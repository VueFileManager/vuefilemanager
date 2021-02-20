<template>
    <div :class="[location, 'emoji-container', {'is-apple': $isApple}]">
        <span v-if="!$isApple()" class="twemoji-emoji emoji-icon" v-html="transferEmoji"></span>
        <span v-if="$isApple()" class="apple-emoji emoji-icon">{{ this.emoji.char }}</span>
    </div>
</template>

<script>
import twemoji from 'twemoji'

export default {
    name: 'Emoji',
    props: [
        'emoji',
        'location',
    ],
    computed: {
        transferEmoji() {
            return twemoji.parse(this.emoji.char, {
                folder: 'svg',
                ext: '.svg',
                attributes: () => ({
                    loading: 'lazy'
                })
            })
        }
    },
}
</script>

<style lang="scss" scoped>
@import "@assets/vue-file-manager/_inapp-forms.scss";
@import '@assets/vue-file-manager/_forms';

.emoji-container {
    font-size: inherit;

    .emoji-icon {
        font-size: inherit;
    }
}

.emoji-picker {
    .apple-emoji {
        font-size: 34px;
        line-height: 1.1;
        font-family: "Apple Color Emoji";
    }
}

.emoji-picker-preview {
    .apple-emoji {
        font-size: 28px;
        line-height: 0.85;
        font-family: "Apple Color Emoji";
    }
}

@media only screen and (max-width: 690px) {
    .groups-list .emoji-picker {
        .apple-emoji {
            font-size: 34px;
            line-height: 1.1;
        }
    }
}

</style>

