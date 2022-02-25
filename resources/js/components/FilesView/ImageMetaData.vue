<template>
    <div>
        <ul class="meta-data-list">
            <li v-if="clipboard.data.attributes.date_time_original">
                <span>{{ $t('file_detail_meta.time_data') }}</span>
                <b>{{ clipboard.data.attributes.date_time_original }}</b>
            </li>

            <li v-if="clipboard.data.attributes.artist">
                <span>{{ $t('file_detail_meta.author') }}</span>
                <b>{{ clipboard.data.attributes.artist }}</b>
            </li>

            <li v-if="clipboard.data.attributes.width && clipboard.data.attributes.height">
                <span>{{ $t('file_detail_meta.dimension') }}</span>
                <b>{{ clipboard.data.attributes.width }}x{{ clipboard.data.attributes.height }}</b>
            </li>

            <li v-if="clipboard.data.attributes.x_resolution && clipboard.data.attributes.y_resolution">
                <span>{{ $t('file_detail_meta.resolution') }}</span>
                <b>{{ clipboard.data.attributes.x_resolution }}x{{ clipboard.data.attributes.y_resolution }}</b>
            </li>

            <li v-if="clipboard.data.attributes.color_space">
                <span> {{ $t('file_detail_meta.color_space') }}</span>
                <b>{{ clipboard.data.attributes.color_space }}</b>
            </li>

			<!--TODO: Colour profile:sRGB IEC61966-2.1-->

            <li v-if="clipboard.data.attributes.make">
                <span>{{ $t('file_detail_meta.make') }}</span>
                <b>{{ clipboard.data.attributes.make }}</b>
            </li>

            <li v-if="clipboard.data.attributes.model">
                <span>{{ $t('file_detail_meta.model') }}</span>
                <b>{{ clipboard.data.attributes.model }}</b>
            </li>

            <li v-if="clipboard.data.attributes.aperture_value">
                <span>{{ $t('file_detail_meta.aperture_value') }}</span>
                <b> {{ clipboard.data.attributes.aperture_value }} </b>
            </li>

            <li v-if="clipboard.data.attributes.exposure_time">
                <span>{{ $t('file_detail_meta.exposure') }}</span>
                <b>{{ clipboard.data.attributes.exposure_time }}</b>
            </li>

            <li v-if="clipboard.data.attributes.focal_length">
                <span>{{ $t('file_detail_meta.focal') }}</span>
                <b>{{ clipboard.data.attributes.focal_length }}</b>
            </li>

            <li v-if="clipboard.data.attributes.iso">
                <span>{{ $t('file_detail_meta.iso') }}</span>
                <b>{{ clipboard.data.attributes.iso }}</b>
            </li>

            <li v-if="clipboard.data.attributes.aperture_f_number">
                <span>{{ $t('file_detail_meta.aperature') }}</span>
                <b>{{ clipboard.data.attributes.aperture_f_number }}</b>
            </li>

            <li v-if="clipboard.data.attributes.ccd_width">
                <span>{{ $t('file_detail_meta.camera_lens') }}</span>
                <b>{{ clipboard.data.attributes.ccd_width }}</b>
            </li>

		  	<li v-if="clipboard.data.attributes.longitude">
                <span>{{ $t('file_detail_meta.longitude') }}</span>
                <b>{{ clipboard.data.attributes.longitude }}</b>
            </li>

			<li v-if="clipboard.data.attributes.latitude">
				<span>{{ $t('file_detail_meta.latitude') }}</span>
				<b>{{ clipboard.data.attributes.latitude }}</b>
			</li>
        </ul>
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

<style lang="scss" scoped>
@import '/resources/sass/vuefilemanager/_variables';
@import '/resources/sass/vuefilemanager/_mixins';

.meta-data-list {
	list-style: none;
	padding: 0px;
	margin: 0px;

	li {
		display: flex;
		justify-content: space-between;
		padding: 9px 0;
		border-bottom: 1px solid $light_mode_border;

		b, span {
			@include font-size(14);
			color: $text;
		}
	}
}

.dark {

	.meta-data-list {
		li {
			border-color: $dark_mode_border_color;

			b, span {
				color: $dark_mode_text_primary !important;
			}
		}
	}
}
</style>