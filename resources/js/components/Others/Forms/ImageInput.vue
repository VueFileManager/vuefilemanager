<template>
    <div
        class="relative flex h-[175px] items-center justify-center rounded-lg bg-light-background dark:bg-2x-dark-foreground"
        :class="{ 'is-error': error }"
    >
        <!--Reset Image-->
        <div
            v-if="imagePreview"
            @click="resetImage"
            class="absolute right-0 top-0 z-[9] flex h-7 w-7 -translate-y-3 translate-x-3 cursor-pointer items-center justify-center rounded-md rounded-full dark:bg-4x-dark-foreground bg-white shadow-lg"
        >
            <x-icon size="14" class="vue-feather dark:text-gray-500" />
        </div>

        <input
            @change="showImagePreview($event)"
            ref="file"
            type="file"
            class="absolute top-0 left-0 right-0 bottom-0 z-10 w-full cursor-pointer opacity-0"
        />

        <!--Default image preview-->
        <img
            v-if="imagePreview"
            :src="imagePreview"
            ref="image"
            class="absolute h-full w-full object-contain py-4 px-12"
        />

        <!--Drop image zone-->
        <div v-if="!isData" class="text-center">
            <image-icon size="34" class="vue-feather text-theme mb-4 inline-block" />

            <b class="block text-base font-bold leading-3">
                {{ $te('input_image.title') ? $t('input_image.title') : 'Upload Image' }}
            </b>
            <small class="text-xs text-gray-500">
                {{
                    $te('input_image.supported')
                        ? $t('input_image.supported')
                        : 'Supported formats are .png, .jpg, .jpeg.'
                }}
            </small>
        </div>
    </div>
</template>

<script>
import { XIcon, ImageIcon } from 'vue-feather-icons'

export default {
    name: 'ImageInput',
    props: ['image', 'error'],
    components: {
        ImageIcon,
        XIcon,
    },
    data() {
        return {
            imagePreview: undefined,
        }
    },
    computed: {
        isData() {
            return !(typeof this.imagePreview === 'undefined' || this.imagePreview === '')
        },
    },
    methods: {
        resetImage() {
            this.imagePreview = undefined
            this.$emit('input', undefined)
        },
        showImagePreview(event) {
            const imgPath = event.target.files[0].name,
                extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase()

            if (['png', 'jpg', 'jpeg', 'svg'].includes(extn)) {
                const file = event.target.files[0],
                    reader = new FileReader()

                reader.onload = () => (this.imagePreview = reader.result)

                reader.readAsDataURL(file)

                // Update user avatar
                this.$emit('input', event.target.files[0])
            } else {
                alert(this.$t('wrong_image_error'))
            }
        },
    },
    created() {
        // If has default image then load
        if (this.image) this.imagePreview = this.image
    },
}
</script>
