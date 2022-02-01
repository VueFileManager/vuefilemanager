<template>
    <div>
        <ul class="meta-data-list">
            <li v-if="clipboard.metadata.DateTimeOriginal">
                <span>{{ $t('file_detail_meta.time_data') }}</span>
                <b>{{ clipboard.metadata.DateTimeOriginal }}</b>
            </li>

            <li v-if="clipboard.metadata.Artist">
                <span>{{ $t('file_detail_meta.author') }}</span>
                <b>{{ clipboard.metadata.Artist }}</b>
            </li>

            <li v-if="clipboard.metadata.ExifImageWidth && clipboard.metadata.ExifImageLength">
                <span>{{ $t('file_detail_meta.dimension') }}</span>
                <b>{{ clipboard.metadata.ExifImageWidth }}x{{ clipboard.metadata.ExifImageLength }}</b>
            </li>

            <li v-if="clipboard.metadata.XResolution && clipboard.metadata.YResolution">
                <span>{{ $t('file_detail_meta.resolution') }}</span>
                <b>{{ clipboard.metadata.XResolution }}x{{ clipboard.metadata.YResolution }}</b>
            </li>

            <li v-if="clipboard.metadata.ColorSpace">
                <span> {{ $t('file_detail_meta.color_space') }}</span>
                <b>{{ clipboard.metadata.ColorSpace }}</b>
            </li>

            <!--TODO: Colour profile:sRGB IEC61966-2.1-->

            <li v-if="clipboard.metadata.Make">
                <span>{{ $t('file_detail_meta.make') }}</span>
                <b>{{ clipboard.metadata.Make }}</b>
            </li>

            <li v-if="clipboard.metadata.Model">
                <span>{{ $t('file_detail_meta.model') }}</span>
                <b>{{ clipboard.metadata.Model }}</b>
            </li>

            <li v-if="clipboard.metadata.ApertureValue">
                <span>{{ $t('file_detail_meta.aperture_value') }}</span>
                <b v-html="parseInt(clipboard.metadata.ApertureValue) / 100"></b>
            </li>

            <li v-if="clipboard.metadata.ExposureTime">
                <span>{{ $t('file_detail_meta.exposure') }}</span>
                <b>{{ clipboard.metadata.ExposureTime }}</b>
            </li>

            <li v-if="clipboard.metadata.FocalLength">
                <span>{{ $t('file_detail_meta.focal') }}</span>
                <b>{{ clipboard.metadata.FocalLength }}</b>
            </li>

            <li v-if="clipboard.metadata.ISOSpeedRatings">
                <span>{{ $t('file_detail_meta.iso') }}</span>
                <b>{{ clipboard.metadata.ISOSpeedRatings }}</b>
            </li>

            <li v-if="clipboard.metadata.COMPUTED.ApertureFNumber">
                <span>{{ $t('file_detail_meta.aperature') }}</span>
                <b>{{ clipboard.metadata.COMPUTED.ApertureFNumber }}</b>
            </li>

            <li v-if="clipboard.metadata.COMPUTED.CCDWidth">
                <span>{{ $t('file_detail_meta.camera_lens') }}</span>
                <b>{{ clipboard.metadata.COMPUTED.CCDWidth }}</b>
            </li>

            <li v-if="clipboard.metadata.GPSLongitude">
                <span>{{ $t('file_detail_meta.longitude') }}</span>
                <b>{{ formatGps(clipboard.metadata.GPSLongitude, clipboard.metadata.GPSLongitudeRef) }}</b>
            </li>

            <li v-if="clipboard.metadata.GPSLatitude">
                <span>{{ $t('file_detail_meta.latitude') }}</span>
                <b>{{ formatGps(clipboard.metadata.GPSLatitude, clipboard.metadata.GPSLatitudeRef) }}</b>
            </li>
        </ul>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { split } from 'lodash'

export default {
    name: 'ImageMetaData',
    computed: {
        clipboard() {
            return this.$store.getters.clipboard[0].data.relationships
        },
    },
    methods: {
        formatGps(location, ref) {
            let data = []

            location.forEach((location) => {
                data.push(split(location, '/', 2)[0])
            })

            return `${data[0]}° ${data[1]}' ${data[2].substr(0, 4) / 100}" ${ref} `
        },
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.meta-data-list {
    list-style: none;
    padding: 0px;
    margin: 0px;

    li {
        display: flex;
        justify-content: space-between;
        padding: 9px 0;
        border-bottom: 1px solid $light_mode_border;

        b,
        span {
            @include font-size(14);
            color: $text;
        }
    }
}

.dark {
    .meta-data-list {
        li {
            border-color: $dark_mode_border_color;

            b,
            span {
                color: $dark_mode_text_primary !important;
            }
        }
    }
}
</style>
