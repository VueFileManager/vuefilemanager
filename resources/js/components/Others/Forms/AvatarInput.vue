<template>
    <div class="relative cursor-pointer">
        <input
			ref="file"
			type="file"
			@change="showImagePreview($event)"
			class="absolute opacity-0 top-0 bottom-0 left-0 right-0 w-full z-10 cursor-pointer"
		/>
        <img
			v-if="imagePreview"
			ref="image"
			:src="imagePreview"
			class="md:w-16 w-14 md:h-16 h-14 object-cover rounded-xl relative z-0 shadow-lg cursor-pointer"
			alt="avatar"
		/>
    </div>
</template>

<script>
    export default {
		name: 'AvatarInput',
		props: [
			'avatar',
		],
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
				let imgPath = event.target.files[0].name,
					extension = imgPath
					.substring(imgPath.lastIndexOf('.') + 1)
					.toLowerCase()

				if (['png', 'jpg', 'jpeg'].includes(extension)) {
					let file = event.target.files[0],
						reader = new FileReader()

					reader.onload = () => (this.imagePreview = reader.result)

					reader.readAsDataURL(file)

					// Update user avatar
					this.$updateImage('/user/settings', 'avatar', event.target.files[0])
				} else {
					alert(this.$t('validation_errors.wrong_image'))
				}
			}
		},
		created() {
			// If there is default image then load
			if (this.avatar) this.imagePreview = this.avatar
		}
	}
</script>
