<template>
    <div class="relative flex items-center justify-center cursor-pointer h-14 w-14 overflow-hidden bg-light-background rounded-xl cursor-pointer z-10">
        <input
            ref="file"
            type="file"
            @change="showImagePreview($event)"
            class="absolute top-0 bottom-0 left-0 right-0 z-10 w-full cursor-pointer opacity-0"
        />
		<camera-icon v-if="!imagePreview" size="22" class="vue-feather text-gray-300" />
        <img
            v-if="imagePreview"
            ref="image"
            :src="imagePreview"
            class="relative w-full h-full z-0 object-cover shadow-lg md:h-16 md:w-16"
            alt="avatar"
        />
    </div>
</template>

<script>
import { CameraIcon} from 'vue-feather-icons'

export default {
    name: 'AvatarInput',
    props: ['avatar'],
	components: {
		CameraIcon,
	},
    data() {
        return {
            imagePreview: undefined,
        }
    },
    watch: {
        imagePreview(val) {
            this.$store.commit('UPDATE_AVATAR', val)
        },
    },
    methods: {
        showImagePreview(event) {
            let imgPath = event.target.files[0].name,
                extension = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase()

            if (['png', 'jpg', 'jpeg'].includes(extension)) {
                let file = event.target.files[0],
                    reader = new FileReader()

                reader.onload = () => (this.imagePreview = reader.result)

                reader.readAsDataURL(file)

                // Update user avatar
                this.$updateAvatar(event.target.files[0])
            } else {
                alert(this.$t('wrong_image_error'))
            }
        },
    },
    created() {
        // If there is default image then load
        if (this.avatar) this.imagePreview = this.avatar
    },
}
</script>
