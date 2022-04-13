<template>
    <div>
		<div class="flex items-center justify-between pt-0.5 pb-2" v-if="clipboard.data.attributes.date_time_original">
			<b class="font-bold text-sm">{{ $t('time_data') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.date_time_original }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.artist">
			<b class="font-bold text-sm">{{ $t('author') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.artist }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.width && clipboard.data.attributes.height">
			<b class="font-bold text-sm">{{ $t('dimension') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.width }}x{{ clipboard.data.attributes.height }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.x_resolution && clipboard.data.attributes.y_resolution">
			<b class="font-bold text-sm">{{ $t('resolution') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.x_resolution }}x{{ clipboard.data.attributes.y_resolution }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.color_space">
			<b class="font-bold text-sm"> {{ $t('color_space') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.color_space }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.make">
			<b class="font-bold text-sm">{{ $t('make') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.make }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.model">
			<b class="font-bold text-sm">{{ $t('model') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.model }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.aperture_value">
			<b class="font-bold text-sm">{{ $t('aperture_value') }}</b>
			<b class="font-bold text-sm"> {{ clipboard.data.attributes.aperture_value }} </b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.exposure_time">
			<b class="font-bold text-sm">{{ $t('exposure') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.exposure_time }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.focal_length">
			<b class="font-bold text-sm">{{ $t('focal') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.focal_length }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.iso">
			<b class="font-bold text-sm">{{ $t('iso') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.iso }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.aperture_f_number">
			<b class="font-bold text-sm">{{ $t('aperature') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.aperture_f_number }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.ccd_width">
			<b class="font-bold text-sm">{{ $t('camera_lens') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.ccd_width }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.longitude">
			<b class="font-bold text-sm">{{ $t('longitude') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.longitude }}</b>
		</div>

		<div class="flex items-center justify-between py-2" v-if="clipboard.data.attributes.latitude">
			<b class="font-bold text-sm">{{ $t('latitude') }}</b>
			<b class="font-bold text-sm">{{ clipboard.data.attributes.latitude }}</b>
		</div>
	</div>
</template>

<script>

export default {
	name: 'ImageMetaData',
	computed: {
		clipboard() {
			return this.$store.getters.clipboard[0].data.relationships.exif
		},
	},
}
</script>