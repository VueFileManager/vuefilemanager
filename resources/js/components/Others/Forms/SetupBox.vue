<template>
    <div class="setup-box" :class="theme">
        <b class="title">{{ title }}</b>
        <p class="description">{{ description }}</p>
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: 'SetupBox',
    props: ['title', 'description', 'theme'],
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/variables';
@import '../../../../sass/vuefilemanager/mixins';

.setup-box {
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;

    .title {
        @include font-size(21);
        margin-bottom: 5px;
        display: block;
        font-weight: 700;
    }

    .description {
        @include font-size(15);
        line-height: 1.5;
        margin-bottom: 20px;
    }

    &.base {
        background: $light_background;
    }

    &.danger {
        background: $light_background;

        .title {
            color: $danger;
        }
    }

    /deep/ input {
        &[type='text'],
        &[type='number'],
        .input-area {
            background: white;
        }
    }

    /deep/ .input-area {
        background: white;
    }

    /deep/ .form {
        margin-top: 20px;

        &.block-form {
            max-width: 450px;

            .single-line-form {
                display: flex;

                .submit-button {
                    margin-left: 20px;
                }
            }
        }
    }
}

@media only screen and (max-width: 960px) {
    .setup-box {
        /deep/ .form {
            &.block-form {
                max-width: 100%;
            }

            input {
                min-width: initial;
            }
        }
    }
}

@media only screen and (max-width: 690px) {
    .setup-box {
        padding: 15px;

        .title {
            @include font-size(17);
            margin-bottom: 10px;
        }

        .description {
            @include font-size(14);
        }

        /deep/ .form.block-form {
            .single-line-form {
                display: block;

                .submit-button {
                    margin-left: 0;
                    margin-top: 10px;
                }
            }
        }
    }
}

.dark {
    .setup-box {
        &.base {
            background: $dark_mode_foreground;
        }

        &.danger {
            background: $dark_mode_foreground;
        }

        /deep/ input {
            &[type='text'],
            &[type='number'],
            .input-area {
                background: $dark_mode_background;
            }
        }

        /deep/ .input-area {
            background: $dark_mode_background;
        }
    }
}
</style>
