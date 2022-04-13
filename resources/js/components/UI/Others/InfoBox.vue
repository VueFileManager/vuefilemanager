<template>
    <div class="info-box" :class="type">
        <slot />
    </div>
</template>

<script>
export default {
    name: 'InfoBox',
    props: ['type'],
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.info-box {
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 32px;
    background: $light_background;
    text-align: left;

    &.error {
        background: rgba($danger, 0.1);

        p,
        a {
            color: $danger;
        }

        a {
            text-decoration: underline;
        }
    }

    p {
        font-size: 15px;
        line-height: 1.6;
        word-break: break-word;
        font-weight: 600;

        /deep/ a {
            font-size: 15px;
        }

        /deep/ b {
            font-size: 15px;
            font-weight: 700;
        }
    }

    b {
        font-weight: 700;
    }

    a {
        font-weight: 700;
        @include font-size(15);
        line-height: 1.6;
    }

    ul {
        margin-top: 15px;
        display: block;

        li {
            display: block;

            a {
                display: block;
            }
        }
    }
}

@media only screen and (max-width: 690px) {
    .info-box {
        padding: 15px;
    }
}

.dark {
    .info-box {
        background: $dark_mode_foreground;

        &.error {
            background: rgba($danger, 0.1);

            p,
            a {
                color: $danger;
            }

            a {
                text-decoration: underline;
            }
        }

        p {
            color: $dark_mode_text_primary;
        }

        ul {
            li {
                color: $dark_mode_text_primary;
            }
        }
    }
}
</style>
