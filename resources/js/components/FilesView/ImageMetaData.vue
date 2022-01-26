<template>
    <div>
        <ul class="meta-data-list">
            <li v-if="clipboard.date_time_original">
                <span>{{ $t('file_detail_meta.time_data') }}</span>
                <b>{{ clipboard.date_time_original }}</b>
            </li>

            <li v-if="clipboard.artist">
                <span>{{ $t('file_detail_meta.author') }}</span>
                <b>{{ clipboard.artist }}</b>
            </li>

            <li v-if="clipboard.width && clipboard.height">
                <span>{{ $t('file_detail_meta.dimension') }}</span>
                <b>{{ clipboard.width }}x{{ clipboard.height }}</b>
            </li>

            <li v-if="clipboard.x_resolution && clipboard.y_resolution">
                <span>{{ $t('file_detail_meta.resolution') }}</span>
                <b>{{ clipboard.x_resolution }}x{{ clipboard.y_resolution }}</b>
            </li>

            <li v-if="clipboard.color_space">
                <span> {{ $t('file_detail_meta.color_space') }}</span>
                <b>{{ clipboard.color_space }}</b>
            </li>

			<!--TODO: Colour profile:sRGB IEC61966-2.1-->

            <li v-if="clipboard.make">
                <span>{{ $t('file_detail_meta.make') }}</span>
                <b>{{ clipboard.make }}</b>
            </li>

            <li v-if="clipboard.model">
                <span>{{ $t('file_detail_meta.model') }}</span>
                <b>{{ clipboard.model }}</b>
            </li>

            <li v-if="clipboard.aperture_value">
                <span>{{ $t('file_detail_meta.aperture_value') }}</span>
                <b> {{ clipboard.aperture_value }} </b>
            </li>

            <li v-if="clipboard.exposure_time">
                <span>{{ $t('file_detail_meta.exposure') }}</span>
                <b>{{ clipboard.exposure_time }}</b>
            </li>

            <li v-if="clipboard.focal_length">
                <span>{{ $t('file_detail_meta.focal') }}</span>
                <b>{{ clipboard.focal_length }}</b>
            </li>

            <li v-if="clipboard.iso">
                <span>{{ $t('file_detail_meta.iso') }}</span>
                <b>{{ clipboard.iso }}</b>
            </li>

            <li v-if="clipboard.aperture_f_number">
                <span>{{ $t('file_detail_meta.aperature') }}</span>
                <b>{{ clipboard.aperture_f_number }}</b>
            </li>

            <li v-if="clipboard.ccd_width">
                <span>{{ $t('file_detail_meta.camera_lens') }}</span>
                <b>{{ clipboard.ccd_width }}</b>
            </li>

		  	<li v-if="clipboard.longitude">
                <span>{{ $t('file_detail_meta.longitude') }}</span>
                <b>{{ clipboard.longitude }}</b>
            </li>

			<li v-if="clipboard.latitude">
				<span>{{ $t('file_detail_meta.latitude') }}</span>
				<b>{{ clipboard.latitude }}</b>
			</li>
        </ul>
    </div>
</template>

<script>

export default {
	name: 'ImageMetaData',
	computed: {
		clipboard() {
			return this.$store.getters.clipboard[0].data.relationships.metadata
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