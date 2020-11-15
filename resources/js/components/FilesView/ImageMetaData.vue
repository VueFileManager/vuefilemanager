<template>
    <div>
        <ul class="meta-data-list">
            <li v-if="fileInfoDetail.metadata.DateTimeOriginal">
                <span>{{ $t('file_detail_meta.time_data') }}</span>
                <b>{{ fileInfoDetail.metadata.DateTimeOriginal }}</b>
            </li>

            <li v-if="fileInfoDetail.metadata.Artist">
                <span>{{ $t('file_detail_meta.author') }}</span>
                <b>{{ fileInfoDetail.metadata.Artist }}</b>
            </li>

            <li v-if="fileInfoDetail.metadata.ExifImageWidth && fileInfoDetail.metadata.ExifImageLength">
                <span>{{ $t('file_detail_meta.dimension') }}</span>
                <b>{{ fileInfoDetail.metadata.ExifImageWidth }}x{{ fileInfoDetail.metadata.ExifImageLength }}</b>
            </li>

            <li v-if="fileInfoDetail.metadata.XResolution && fileInfoDetail.metadata.YResolution">
                <span>{{ $t('file_detail_meta.resolution') }}</span>
                <b>{{ fileInfoDetail.metadata.XResolution }}x{{ fileInfoDetail.metadata.YResolution }}</b>
            </li>

            <li v-if="fileInfoDetail.metadata.ColorSpace">
                <span> {{ $t('file_detail_meta.color_space') }}</span>
                <b>{{ fileInfoDetail.metadata.ColorSpace}}</b>
            </li>

            <!--TODO: Colour profile:sRGB IEC61966-2.1-->

            <li v-if="fileInfoDetail.metadata.Make">
                <span>{{ $t('file_detail_meta.make') }}</span>
                <b>{{ fileInfoDetail.metadata.Make }}</b>
            </li>

            <li v-if="fileInfoDetail.metadata.Model">
                <span>{{ $t('file_detail_meta.model') }}</span>
                <b>{{ fileInfoDetail.metadata.Model }}</b>
            </li>

            <li v-if="fileInfoDetail.metadata.ApertureValue">
                <span>{{ $t('file_detail_meta.aperture_value') }}</span>
                <b v-html="parseInt(fileInfoDetail.metadata.ApertureValue) / 100"></b>
            </li>

            <li v-if="fileInfoDetail.metadata.ExposureTime">
                <span>{{ $t('file_detail_meta.exposure') }}</span>
                <b>{{ fileInfoDetail.metadata.ExposureTime }}</b>
            </li>

            <li v-if="fileInfoDetail.metadata.FocalLength">
                <span>{{ $t('file_detail_meta.focal') }}</span>
                <b>{{ fileInfoDetail.metadata.FocalLength }}</b>
            </li>

            <li v-if="fileInfoDetail.metadata.ISOSpeedRatings">
                <span>{{ $t('file_detail_meta.iso') }}</span>
                <b>{{ fileInfoDetail.metadata.ISOSpeedRatings }}</b>
            </li>

            <li v-if="fileInfoDetail.metadata.COMPUTED.ApertureFNumber">
                <span>{{ $t('file_detail_meta.aperature') }}</span>
                <b>{{ fileInfoDetail.metadata.COMPUTED.ApertureFNumber }}</b>
            </li>

            <li v-if="fileInfoDetail.metadata.COMPUTED.CCDWidth">
                <span>{{ $t('file_detail_meta.camera_lens') }}</span>
                <b>{{ fileInfoDetail.metadata.COMPUTED.CCDWidth }}</b>
            </li>

              <li v-if="fileInfoDetail.metadata.GPSLongitude">
                <span>{{ $t('file_detail_meta.longitude') }}</span>
                <b>{{ formatGps(fileInfoDetail.metadata.GPSLongitude,fileInfoDetail.metadata.GPSLongitudeRef) }}</b>
            </li>

               <li v-if="fileInfoDetail.metadata.GPSLatitude">
                <span>{{ $t('file_detail_meta.latitude') }}</span>
                <b>{{ formatGps(fileInfoDetail.metadata.GPSLatitude, fileInfoDetail.metadata.GPSLatitudeRef) }}</b>
            </li>

        </ul>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import {split} from 'lodash'

export default {
    name: 'ImageMetaData',
    computed: {
    fileInfoDetail() {
        return this.$store.getters.fileInfoDetail[0]
    },
    },
    methods: {
        formatGps(location, ref) {
            let data = []
            location.forEach(location => {
                data.push(split(location , '/' , 2)[0])
            })
          return `${data[0]}° ${data[1]}' ${data[2].substr(0,4) / 100}" ${ref} `
        }
    },
    
}
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

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

@media (prefers-color-scheme: dark) {

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