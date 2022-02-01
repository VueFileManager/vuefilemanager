<template>
    <div class="page-title left" :class="type">
        <h1 class="title" v-html="title"></h1>
        <h2 class="description" v-if="description">
            {{ description }}
        </h2>
    </div>
</template>

<script>
export default {
    name: 'IndexPageTile',
    props: ['title', 'description', 'type'],
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/landing-page';
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.page-title {
    position: relative;
    z-index: 1;

    &.center {
        text-align: center;

        .title {
            margin-left: auto;
            margin-right: auto;
            max-width: 780px;
        }

        .description {
            margin-left: auto;
            margin-right: auto;
        }
    }

    .title {
        max-width: 580px;
        font-size: 48px;
        font-weight: 800;
        line-height: 1.3;
        margin-bottom: 15px;

        /deep/ span {
            font-size: 48px;
        }
    }

    .description {
        max-width: 580px;
        @include font-size(20);
        font-weight: 500;
        line-height: 1.65;
        margin-bottom: 30px;
    }
}

@media only screen and (max-width: 960px) {
    .page-title {
        .title {
            max-width: 100%;
            font-size: 32px;
            line-height: 1.25;
            margin-bottom: 15px;

            /deep/ span {
                font-size: 32px;
            }
        }

        .description {
            max-width: 100%;
            @include font-size(16);
            line-height: 1.6;
            margin-bottom: 30px;
        }
    }
}
</style>
