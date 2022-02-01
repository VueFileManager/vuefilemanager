<template>
    <div class="page-header">
        <div class="go-back" v-if="canBack" @click="$router.back()">
            <chevron-left-icon size="17" />
        </div>
        <div class="content">
            <h1 class="title">{{ title }}</h1>
        </div>
    </div>
</template>

<script>
import { ChevronLeftIcon } from 'vue-feather-icons'

export default {
    name: 'PageHeader',
    props: ['title', 'canBack'],
    components: {
        ChevronLeftIcon,
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.page-header {
    display: flex;
    align-items: center;
    background: white;
    z-index: 9;
    width: 100%;
    position: sticky;
    top: 0;
    padding-top: 20px;
    padding-bottom: 20px;

    .title {
        @include font-size(18);
        font-weight: 700;
        color: $text;
    }

    .go-back {
        margin-right: 10px;
        cursor: pointer;

        svg {
            vertical-align: middle;
            margin-top: -4px;
        }
    }
}

@media only screen and (max-width: 960px) {
    .page-header {
        .title {
            @include font-size(18);
        }
    }
}

@media only screen and (max-width: 690px) {
    .page-header {
        display: none;
    }
}

.dark {
    .page-header {
        background: $dark_mode_background;

        .title {
            color: $dark_mode_text_primary;
        }

        .icon path {
            fill: $theme;
        }
    }
}
</style>
