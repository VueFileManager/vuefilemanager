<template>
    <div class="dropzone" :class="{ 'is-error': error }">
        <input
                ref="file"
                type="file"
                @change="showImagePreview($event)"
                :name="name"
                class="dummy"
        />
        <img
                ref="image"
                :src="imagePreview"
                class="image-preview"
                v-if="imagePreview"
        />
    </div>
</template>

<script>

    export default {
        props: ['label', 'name', 'avatar', 'info', 'error'],
        data() {
            return {
                imagePreview: undefined
            }
        },
        watch: {
            imagePreview(val) {
                this.$store.commit('UPDATE_AVATAR', val)
            }
        },
        methods: {
            showImagePreview(event) {
                const imgPath = event.target.files[0].name,
                    extn = imgPath
                        .substring(imgPath.lastIndexOf('.') + 1)
                        .toLowerCase()

                if (['png', 'jpg', 'jpeg'].includes(extn)) {
                    const file = event.target.files[0],
                        reader = new FileReader()

                    reader.onload = () => (this.imagePreview = reader.result)

                    reader.readAsDataURL(file)

                    // Update user avatar
                    this.$updateImage('/user/profile', 'avatar', event.target.files[0])
                } else {
                    alert( this.$t('validation_errors.wrong_image') )
                }
            }
        },
        created() {
            // If has default image then load
            if (this.avatar) this.imagePreview = this.avatar
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .dropzone {
        position: relative;
        line-height: 0;

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
            width: 62px;
            height: 62px;
            object-fit: cover;
            border-radius: 8px;
        }
    }
</style>
