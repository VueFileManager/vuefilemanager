<template>
    <div class="flex items-center justify-center rounded-lg relative h-[175px] bg-light-background" :class="{ 'is-error': error }">

		<!--Reset Image-->
		<div
			v-if="imagePreview"
			@click="resetImage"
			class="absolute z-10 right-0 top-0 flex h-7 w-7 cursor-pointer items-center justify-center rounded-md bg-white shadow-lg rounded-full -translate-y-3 translate-x-3"
		>
            <x-icon size="14" class="vue-feather" />
		</div>

        <input
			@change="showImagePreview($event)"
			ref="file"
			type="file"
			class="opacity-0 absolute top-0 left-0 right-0 bottom-0 z-10 w-full cursor-pointer"
		/>

		<!--Default image preview-->
		<img v-if="imagePreview" :src="imagePreview" ref="image" class="absolute w-full h-full object-contain py-4 px-12" />

		<!--Drop image zone-->
        <div v-if="!isData" class="text-center">
            <image-icon size="34" class="vue-feather text-theme inline-block mb-4" />

			<b class="font-bold text-base block leading-3">
                {{ $t('input_image.title') }}
            </b>
            <small class="text-xs text-gray-500">
                {{ $t('input_image.supported') }}
            </small>
        </div>
    </div>
</template>

<script>
import {XIcon, ImageIcon} from 'vue-feather-icons'

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
				alert(this.$t('validation_errors.wrong_image'))
			}
		},
	},
	created() {
		// If has default image then load
		if (this.image) this.imagePreview = this.image
	},
}
</script>
