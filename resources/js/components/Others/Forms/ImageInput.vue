<template>
    <div class="dropzone" :class="{ 'is-error': error }">
        <div v-if="imagePreview" @click="resetImage" class="reset-image">
            <x-icon size="14" class="close-icon"></x-icon>
        </div>

        <input
                ref="file"
                type="file"
                @change="showImagePreview($event)"
                class="dummy"
        />
        <img
                ref="image"
                :src="imagePreview"
                class="image-preview"
                v-if="imagePreview"
        />

        <div class="dropzone-message" v-show="! isData">
            <image-icon size="28" class="icon-upload"></image-icon>
            <span class="dropzone-title">
                {{ $t('input_image.title') }}
            </span>
            <span class="dropzone-description">
                {{ $t('input_image.supported') }}
            </span>
        </div>
    </div>
</template>

<script>
    import { XIcon, ImageIcon } from 'vue-feather-icons'

    export default {
        name: 'ImageInput',
        props: [
            'image', 'error'
        ],
        components: {
            ImageIcon,
            XIcon,
        },
        data() {
            return {
                imagePreview: undefined
            }
        },
        computed: {
            isData() {
                return typeof this.imagePreview === 'undefined' || this.imagePreview === '' ? false : true
            },
        },
        methods: {
            resetImage() {
                this.imagePreview = undefined
                this.$emit('input', undefined)
            },
            showImagePreview(event) {
                const imgPath = event.target.files[0].name,
                    extn = imgPath
                        .substring(imgPath.lastIndexOf('.') + 1)
                        .toLowerCase()

                if (['png', 'jpg', 'jpeg', 'svg'].includes(extn)) {
                    const file = event.target.files[0],
                        reader = new FileReader()

                    reader.onload = () => (this.imagePreview = reader.result)

                    reader.readAsDataURL(file)

                    // Update user avatar
                    this.$emit('input', event.target.files[0])
                } else {
                    alert( this.$t('validation_errors.wrong_image') )
                }
            }
        },
        created() {
            // If has default image then load
            if (this.image) this.imagePreview = this.image
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .dropzone {
        border: 1px dashed #a1abc2;
        border-radius: 8px;
        position: relative;
        text-align: center;
        display: flex;
        align-items: center;
        min-height: 175px;

        &.is-error {
            border: 2px dashed rgba(253, 57, 122, 0.3);

            .dropzone-title {
                color: $danger;
            }

            .icon-upload {
                rect, circle, polyline {
                    stroke: $danger
                }
            }
        }

        input[type='file'] {
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            width: 100%;
            cursor: pointer;
        }

        .image-preview {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: contain;
            left: 0;
            padding: 25px;
            display: block;

            &.fit-image {
                object-fit: cover;
                border-radius: 12px;
                overflow: hidden;

            }
        }

        .dropzone-message {
            padding: 50px 0;
            width: 100%;

            .icon-upload {
                rect, circle, polyline {
                    stroke: $theme
                }
            }

            .dropzone-title {
                @include font-size(16);
                font-weight: 700;
                display: block;
            }

            .dropzone-description {
                color: $text_muted;
                @include font-size(12);
            }
        }

        .reset-image {
            z-index: 2;
            background: white;
            border-radius: 50px;
            display: block;
            position: absolute;
            right: 0;
            top: 0;
            cursor: pointer;
            @include transform(translateY(-50%) translateX(50%));
            padding: 0px 4px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);

            .close-icon {
                vertical-align: middle;

                line {
                    path {
                        fill: $text;
                    }
                }
            }
        }
    }

    @media (prefers-color-scheme: dark) {
        .dropzone {
            border-color: rgba(white, 0.2);

            .dropzone-message {

                .icon-upload {
                    path, polyline, line {
                        stroke: $theme;
                    }
                }

                .dropzone-description {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }
</style>
