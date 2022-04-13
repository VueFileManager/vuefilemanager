<template>
    <div class="flex shrink-0 grow-0 items-center">
        <MemberAvatar class="mr-3 shrink-0" :is-border="false" :size="52" :member="member" />
        <div class="info">
            <b class="name" v-if="title">{{ title }}</b>
            <span class="description" v-if="description">{{ description }}</span>
        </div>
    </div>
</template>

<script>
import MemberAvatar from '../Others/MemberAvatar'

export default {
    name: 'DatatableCellImage',
    props: ['member', 'title', 'description', 'image-size'],
    components: {
        MemberAvatar,
    },
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.info {
    .name,
    .description {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    .name {
        @include font-size(15);
        line-height: 1;
        color: $text;
    }

    .description {
        color: $text-muted;
        @include font-size(12);
    }
}

.dark {
    .cell-image-thumbnail {
        .image {
            img {
                &.blurred {
                    display: none;
                }
            }
        }

        .info {
            .name {
                color: $dark_mode_text_primary;
            }

            .description {
                color: $dark_mode_text_secondary;
            }
        }
    }
}
</style>
